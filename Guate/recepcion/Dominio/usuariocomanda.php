<?php
class UsuarioComanda{
    var $idComanda;
    var $numComanda;
    var $procedencia;
    var $cobrado;
    var $fechaHora;
    var $total;
    var $clientType;
    var $nombre;
	
	function UsuarioComanda($idComanda,$numComanda,$procedencia,$cobrado,$fechaHora,$total,$clientType,$nombre){
	$this->idComanda = $idComanda;
	$this->numComanda = $numComanda;
	$this->procedencia = $procedencia;
	$this->cobrado = $cobrado;
	$this->fechaHora = $fechaHora;
	$this->total = $total;
	$this->clientType= $clientType;
	$this->nombre = $nombre;
	}
}
?>
