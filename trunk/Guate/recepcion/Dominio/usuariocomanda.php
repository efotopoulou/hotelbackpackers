<?php
class UsuarioComanda{
    var $idComanda;
    var $numComanda;
    var $cobrado;
    var $fechaHora;
    var $total;
    var $clientType;
    var $nombre;
	
	function UsuarioComanda($idComanda,$numComanda,$cobrado,$fechaHora,$total,$clientType,$nombre){
	$this->idComanda = $idComanda;
	$this->numComanda = $numComanda;
	$this->cobrado = $cobrado;
	$this->fechaHora = $fechaHora;
	$this->total = $total;
	$this->clientType= $clientType;
	$this->nombre = $nombre;
	}
}
?>
