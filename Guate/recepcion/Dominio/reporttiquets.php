<?php
class ReportTiquets{
    var $fecha;
    var $time;
    var $idComanda;
    var $total;

	
	function ReportTiquets($fecha,$time,$idComanda,$total){
	$this->fecha = $fecha;
	$this->time = $time;
	$this->idComanda = $idComanda;
	$this->total = $total;
	}
}
?>

