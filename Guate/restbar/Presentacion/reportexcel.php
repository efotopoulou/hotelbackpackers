<?php
require($_SERVER['DOCUMENT_ROOT'] . '/restbar/Presentacion/reporte.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$idcaja =  $_GET['idcaja'];

/** Error reporting */
error_reporting(E_ALL);

$mensaje = new MensajeJSON();

if ($idcaja){
error_reporting(E_ERROR);
$reporte = new getreporte();
$response=$reporte->getdatosExcel($idcaja,$mensaje);	
}else{
error_reporting(E_ERROR);
$caja=new caja();
$id_caja=$caja->get_id_caja ();
$reporte = new getreporte();
$response=$reporte->getdatosExcel($id_caja,$mensaje);
}
/** Include path **/

//set_include_path(get_include_path() . PATH_SEPARATOR . '../Datos/Classes/');
set_include_path($_SERVER['DOCUMENT_ROOT'].'/common/Excel/');


/** PHPExcel */
include 'PHPExcel.php';
/** PHPExcel_RichText */
require_once 'PHPExcel/RichText.php';

/** PHPExcel_IOFactory */
include 'PHPExcel/IOFactory.php';


header('Content-type: application/xls');
header('Content-Disposition: attachment; filename="reportexcel.xls"');
// Create new PHPExcel object
//echo date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Set properties
$objPHPExcel->getProperties()->setTitle("Caja report");

$turno =  $_GET['turno'];
$encargado =  $_GET['encargado'];
//$caja=new caja();
//$nombre = $caja->nameEncargado($encargado);

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A10')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A12')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A15')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B15')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C15')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D15')->getFont()->setBold(true);

// Add some data
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Hotel Backpapers Bar-Restaurante');
$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Resumen de caja');
$objPHPExcel->getActiveSheet()->setCellValue('A6', 'Desde:');
$objPHPExcel->getActiveSheet()->setCellValue('B6', $response["HoraApertura"]);
$objPHPExcel->getActiveSheet()->setCellValue('C6', 'Hasta:');
if($response["HoraCierre"]) $objPHPExcel->getActiveSheet()->setCellValue('D6', $response["HoraCierre"]);

$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Turno:');
$objPHPExcel->getActiveSheet()->setCellValue('B8', $turno);
$objPHPExcel->getActiveSheet()->setCellValue('A10', 'Encargado:');
$objPHPExcel->getActiveSheet()->setCellValue('B10', $encargado);
$objPHPExcel->getActiveSheet()->setCellValue('A12', 'Fecha y hora de Impresion:');
$objPHPExcel->getActiveSheet()->setCellValue('B12', time());
$objPHPExcel->getActiveSheet()->getStyle('B12')->getNumberFormat()->setFormatCode('dd/mm/yyyy H:mm:ss');


$objPHPExcel->getActiveSheet()->setCellValue('A15', 'Cuenta');
$objPHPExcel->getActiveSheet()->setCellValue('B15', 'Ingresos');
$objPHPExcel->getActiveSheet()->setCellValue('C15', 'Egresos');
$objPHPExcel->getActiveSheet()->setCellValue('D15', 'Saldo');

$i=16;
foreach($response["Info"] as $indice => $valor) {
	//echo ('A'.$i.": ".$indice."->".$response["Info"][$indice]["entrada"]);
 //$categoria[$indice]=0;
 $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$indice);
 $objPHPExcel->getActiveSheet()->setCellValue('B'.$i,$valor["entrada"]);
 $objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$valor["salida"]);
 $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, '=ABS(B'.$i.'-C'.$i.')');
 $i++;
}

$objPHPExcel->getActiveSheet()->setCellValue('A'.($i+1),'TOTALES');
$objPHPExcel->getActiveSheet()->setCellValue('B'.($i+1),'=SUM(B16:B'.$i.')');
$objPHPExcel->getActiveSheet()->setCellValue('C'.($i+1),'=SUM(C16:C'.$i.')');
$objPHPExcel->getActiveSheet()->setCellValue('D'.($i+1),'=B'.($i+1).'-C'.($i+1));

//$objPHPExcel->getActiveSheet()->setCellValue('D'.($i+1),'=SUM(D16:D'.$i.')');

$objPHPExcel->getActiveSheet()->getStyle('A'.($i+1))->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B'.($i+1))->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C'.($i+1))->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.($i+1))->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('C'.($i+3),'TOTAL ENTRADA');
$objPHPExcel->getActiveSheet()->setCellValue('D'.($i+3),'=B'.($i+1));
$objPHPExcel->getActiveSheet()->setCellValue('C'.($i+4),'TOTAL SALIDA');
$objPHPExcel->getActiveSheet()->setCellValue('D'.($i+4),'=C'.($i+1));
$objPHPExcel->getActiveSheet()->setCellValue('C'.($i+5),'TOTAL EFECTIVO');
$objPHPExcel->getActiveSheet()->setCellValue('D'.($i+5),'=D'.($i+1));

$ultimarow = $objPHPExcel->getActiveSheet()->getHighestRow();
$ulrow = $ultimarow+3;
$objPHPExcel->getActiveSheet()->getStyle('A'.$ulrow)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A'.($ulrow+1))->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B'.($ulrow+1))->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C'.($ulrow+1))->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.($ulrow+1))->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('A'.($ulrow), 'Venta de Turno');
$objPHPExcel->getActiveSheet()->setCellValue('A'.($ulrow+1), 'Codigo');
$objPHPExcel->getActiveSheet()->setCellValue('B'.($ulrow+1), 'Nombre');
$objPHPExcel->getActiveSheet()->setCellValue('C'.($ulrow+1), 'Cantidad');
$objPHPExcel->getActiveSheet()->setCellValue('D'.($ulrow+1), 'Dinero');

$w=$ulrow+3;
foreach($response["VentaTurno"] as $indice => $valor) {
	//echo ('A'.$i.": ".$indice."->".$response["Info"][$indice]["entrada"]);
 //$categoria[$indice]=0;
 //for($j=0;$j<sizeof($valor);$j++){
 $objPHPExcel->getActiveSheet()->setCellValue('A'.($w),$valor["numBebida"]);
 $objPHPExcel->getActiveSheet()->setCellValue('B'.($w),$valor["nombre"]);
 $objPHPExcel->getActiveSheet()->setCellValue('C'.($w),$valor["suma"]);
 $objPHPExcel->getActiveSheet()->setCellValue('D'.($w),$valor["precio"]);
 //}
 $w++;
}

// Set column widths
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);

// Rename sheet
//echo date('H:i:s') . " Rename sheet\n";
$objPHPExcel->getActiveSheet()->setTitle('Resumen');


$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'INFORME DE CAJA POR TURNO');
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Fecha');
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Cuenta');
$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Valores');
$objPHPExcel->getActiveSheet()->setCellValue('D3', 'Detalle');
$objPHPExcel->getActiveSheet()->setCellValue('E3', 'Entrada');
$objPHPExcel->getActiveSheet()->setCellValue('F3', 'Salida');
$objPHPExcel->getActiveSheet()->setCellValue('G3', 'Factura');

$i=4;
if($response["ReportDetails"]){
  foreach($response["ReportDetails"] as $indice => $valor) {
	//echo ('A'.$i.": ".$indice."->".$response["Info"][$indice]["entrada"]);
 
 for($j=0;$j<sizeof($valor);$j++){
 $objPHPExcel->getActiveSheet()->setCellValue('A'.($i+$j),$valor[$j]["date"]);	
 $objPHPExcel->getActiveSheet()->setCellValue('B'.($i+$j),$indice);
 $objPHPExcel->getActiveSheet()->setCellValue('C'.($i+$j),'efectivo');
 $objPHPExcel->getActiveSheet()->setCellValue('D'.($i+$j),$valor[$j]["descripcion"]);
 $objPHPExcel->getActiveSheet()->setCellValue('E'.($i+$j),$valor[$j]["entrada"]);
 $objPHPExcel->getActiveSheet()->setCellValue('F'.($i+$j),$valor[$j]["salida"]);
 $objPHPExcel->getActiveSheet()->setCellValue('G'.($i+$j),' - ');
 }
 $i+=sizeof($valor);
  }
}
foreach($response["Tiquets"] as $indice => $valor) {
 //for($j=0;$j<sizeof($valor);$j++){
 $objPHPExcel->getActiveSheet()->setCellValue('A'.($i),$valor["fecha"]);
 $objPHPExcel->getActiveSheet()->setCellValue('B'.($i),'lalala');
 if($valor["idComanda"] !="") $objPHPExcel->getActiveSheet()->setCellValue('B'.($i),'Adicion Restaurante');
 else $objPHPExcel->getActiveSheet()->setCellValue('B'.($i),'Adicion Bar');
 $objPHPExcel->getActiveSheet()->setCellValue('C'.($i),'efectivo');
 $objPHPExcel->getActiveSheet()->setCellValue('D'.($i),$valor["idComanda"]);
 $objPHPExcel->getActiveSheet()->setCellValue('E'.($i),$valor["total"]);
 $objPHPExcel->getActiveSheet()->setCellValue('F'.($i),'0');
 $objPHPExcel->getActiveSheet()->setCellValue('G'.($i),' - ');
 //}
 $i++;
}

$lastrow = $objPHPExcel->getActiveSheet()->getHighestRow();
$row = $lastrow+3;
if($response["ReportDetails"]){
 foreach($response["ReportDetails"] as $indice => $valor) {
 
 //$categoria[$indice]=1;
 $objPHPExcel->getActiveSheet()->setCellValue('A'.($row-1),$indice);
 $objPHPExcel->getActiveSheet()->setCellValue('D'.($row-1),'Ingreso');
 $objPHPExcel->getActiveSheet()->setCellValue('E'.($row-1),'Egreso');
 
 $objPHPExcel->getActiveSheet()->getStyle('A'.($row-1))->getFont()->setBold(true);
 $objPHPExcel->getActiveSheet()->getStyle('D'.($row-1))->getFont()->setBold(true);
 $objPHPExcel->getActiveSheet()->getStyle('E'.($row-1))->getFont()->setBold(true);
 
 for($j=0;$j<sizeof($valor);$j++){
 $objPHPExcel->getActiveSheet()->setCellValue('A'.($row+$j),$valor[$j]["time"]);	
 $objPHPExcel->getActiveSheet()->setCellValue('B'.($row+$j),$valor[$j]["descripcion"]);
 $objPHPExcel->getActiveSheet()->setCellValue('C'.($row+$j),'efectivo');
 $objPHPExcel->getActiveSheet()->setCellValue('D'.($row+$j),$valor[$j]["entrada"]);
 $objPHPExcel->getActiveSheet()->setCellValue('E'.($row+$j),$valor[$j]["salida"]);
 }
 $objPHPExcel->getActiveSheet()->setCellValue('C'.($row+sizeof($valor)),'TOTAL');
 $objPHPExcel->getActiveSheet()->setCellValue('D'.($row+sizeof($valor)),'=SUM(D'.$row.':D'.($row+sizeof($valor)-1).')');
 $objPHPExcel->getActiveSheet()->setCellValue('E'.($row+sizeof($valor)),'=SUM(E'.$row.':E'.($row+sizeof($valor)-1).')');
 $row+=sizeof($valor)+4;
 }
}
 $objPHPExcel->getActiveSheet()->setCellValue('A'.($row-1),'Adicion Bar');
 $objPHPExcel->getActiveSheet()->setCellValue('D'.($row-1),'Ingreso');
 $objPHPExcel->getActiveSheet()->setCellValue('E'.($row-1),'Egreso');
 
 $objPHPExcel->getActiveSheet()->getStyle('A'.($row-1))->getFont()->setBold(true);
 $objPHPExcel->getActiveSheet()->getStyle('D'.($row-1))->getFont()->setBold(true);
 $objPHPExcel->getActiveSheet()->getStyle('E'.($row-1))->getFont()->setBold(true);

$aux=$row;
foreach($response["Tiquets"] as $indice => $valor) {
 //$objPHPExcel->getActiveSheet()->setCellValue('A'.($row-1),$indice);
 //$objPHPExcel->getActiveSheet()->setCellValue('D'.($row-1),'Ingreso');
 //$objPHPExcel->getActiveSheet()->setCellValue('E'.($row-1),'Egreso');
 
 if ($valor["idComanda"]== ""){
  $objPHPExcel->getActiveSheet()->setCellValue('A'.($row),$valor["time"]);
  $objPHPExcel->getActiveSheet()->setCellValue('B'.($row),$valor["idComanda"]);
  $objPHPExcel->getActiveSheet()->setCellValue('C'.($row),'efectivo');
  $objPHPExcel->getActiveSheet()->setCellValue('D'.($row),$valor["total"]);
  $objPHPExcel->getActiveSheet()->setCellValue('E'.($row),'0');
  $row++;
 }
}
 $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,'TOTAL');
 $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,'=SUM(D'.$aux.':D'.($row-1).')');
 $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,'0');
 $row+=sizeof($valor)+2;
 
 $objPHPExcel->getActiveSheet()->setCellValue('A'.($row-1),'Adicion Restaurante');
 $objPHPExcel->getActiveSheet()->setCellValue('D'.($row-1),'Ingreso');
 $objPHPExcel->getActiveSheet()->setCellValue('E'.($row-1),'Egreso');
 
 $objPHPExcel->getActiveSheet()->getStyle('A'.($row-1))->getFont()->setBold(true);
 $objPHPExcel->getActiveSheet()->getStyle('D'.($row-1))->getFont()->setBold(true);
 $objPHPExcel->getActiveSheet()->getStyle('E'.($row-1))->getFont()->setBold(true);

$aux=$row;
 foreach($response["Tiquets"] as $indice => $valor) {
 //$objPHPExcel->getActiveSheet()->setCellValue('A'.($row-1),$indice);
 //$objPHPExcel->getActiveSheet()->setCellValue('D'.($row-1),'Ingreso');
 //$objPHPExcel->getActiveSheet()->setCellValue('E'.($row-1),'Egreso');
 
 if ($valor["idComanda"]!= ""){
  $objPHPExcel->getActiveSheet()->setCellValue('A'.($row),$valor["time"]);
  $objPHPExcel->getActiveSheet()->setCellValue('B'.($row),$valor["idComanda"]);
  $objPHPExcel->getActiveSheet()->setCellValue('C'.($row),'efectivo');
  $objPHPExcel->getActiveSheet()->setCellValue('D'.($row),$valor["total"]);
  $objPHPExcel->getActiveSheet()->setCellValue('E'.($row),'0');
  $row++;
 }
}
 $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,'TOTAL');
 $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,'=SUM(D'.$aux.':D'.($row-1).')');
 $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,'0');
 $row+=sizeof($valor)+2;
 

// Set column widths
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setTitle('Details');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_clean();
$objWriter->save("php://output");

?>

