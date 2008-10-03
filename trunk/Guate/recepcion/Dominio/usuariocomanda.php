<?php
class UsuarioComanda{
    var $idComanda;
    var $numComanda;
    var $estado;
    var $fechaHora;
    var $total;
    var $clientType;
    var $nombre;
	
	function UsuarioComanda($idComanda,$numComanda,$estado,$fechaHora,$total,$clientType,$nombre){
	$this->idComanda = $idComanda;
	$this->numComanda = $numComanda;
	$this->estado = $estado;
	$this->fechaHora = $fechaHora;
	$this->total = $total;
	$this->clientType= $clientType;
	$this->nombre = $nombre;
	}
}
?>
