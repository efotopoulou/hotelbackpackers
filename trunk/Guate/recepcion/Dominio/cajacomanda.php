<?php
class CajaComanda{
    var $idComanda;
    var $estado;
    var $fechaHora;
    var $total;
    var $efectivo;
    var $tipoCliente;
    var $nombre;
    var $free;
	
	function CajaComanda($idComanda,$estado,$fechaHora,$total,$efectivo,$tipoCliente,$nombre,$free){
	$this->idComanda = $idComanda;
	$this->estado = $estado;
	$this->fechaHora = $fechaHora;
	$this->total = $total;
	$this->efectivo = $efectivo;
	$this->estado = $estado;
	$this->tipoCliente= $tipoCliente;
	$this->nombre = $nombre;
	$this->free =$free;
	}
}
?>