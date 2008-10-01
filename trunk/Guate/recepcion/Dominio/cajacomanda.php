<?php
class CajaComanda{
    var $idComanda;
    var $numComanda;
    var $fechaHora;
    var $total;
    var $efectivo;
    var $tipoCliente;
    var $nombre;
    var $free;
	
	function CajaComanda($idComanda,$numComanda,$fechaHora,$total,$efectivo,$tipoCliente,$nombre,$free){
	$this->idComanda = $idComanda;
	$this->numComanda = $numComanda;
	$this->fechaHora = $fechaHora;
	$this->total = $total;
	$this->efectivo = $efectivo;
	$this->tipoCliente= $tipoCliente;
	$this->nombre = $nombre;
	$this->free =$free;
	}
}
?>