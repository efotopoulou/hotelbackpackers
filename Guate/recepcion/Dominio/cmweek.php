<?php
class CmWeek{
    var $id_caja;
    var $suma;
    var $entradas;
    var $salidas;
    var $fechaHoraApertura;
    var $numday;
    var $fecha;
    var $mes;
    var $anyo;
	
	function CmWeek($id_caja,$suma,$entradas,$salidas,$fechaHoraApertura,$numday,$fecha,$mes,$anyo){
	$this->id_caja = $id_caja;
	$this->suma = $suma;
	$this->entradas= $entradas;
	$this->salidas = $salidas;
	$this->fechaHoraApertura = $fechaHoraApertura;
	$this->numday = $numday;
	$this->fecha = $fecha;
	$this->mes = $mes;
	$this->anyo = $anyo;
	}
  
	
	}
?>
