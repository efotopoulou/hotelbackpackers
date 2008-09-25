<?php
class FindCaja{
    var $id_caja;
    var $fechaHoraApertura;
    var $fechaHoraCierre;
    var $fondoInicial;
    var $EfectivoCerrar;
	
	function FindCaja($id_caja,$fechaHoraApertura,$fechaHoraCierre,$fondoInicial,$EfectivoCerrar){
	$this->id_caja = $id_caja;
	$this->fechaHoraApertura = $fechaHoraApertura;
	$this->fechaHoraCierre= $fechaHoraCierre;
	$this->fondoInicial = $fondoInicial;
	$this->EfectivoCerrar = $EfectivoCerrar;
	}
  
	
	}
?>
