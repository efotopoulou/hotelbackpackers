<?php
class UsuarioComanda{
    var $idComanda;
    var $numComanda;
    var $estado;
    var $fechaHora;
    var $total;
    var $efectivo;
    var $clientType;
    var $nombre;
	
	function UsuarioComanda($idComanda,$numComanda,$estado,$fechaHora,$total,$efectivo,$clientType,$nombre){
	$this->idComanda = $idComanda;
	$this->numComanda = $numComanda;
	$this->estado = $estado;
	$this->fechaHora = $fechaHora;
	$this->total = $total;
	$this->efectivo = $efectivo;
	$this->estado = $estado;
	$this->clientType= $clientType;
	$this->nombre = $nombre;
	}
}
?>
