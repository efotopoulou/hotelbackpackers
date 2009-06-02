<?php
class UsuarioComanda{
    var $idComanda;
    var $numComanda;
    var $procedencia;
    var $fechaHora;
    var $total;
    var $nombre;
	
	function UsuarioComanda($idComanda,$numComanda,$procedencia,$fechaHora,$total,$nombre){
	$this->idComanda = $idComanda;
	$this->numComanda = $numComanda;
	$this->procedencia = $procedencia;
	$this->fechaHora = $fechaHora;
	$this->total = $total;
	$this->nombre = $nombre;
	}
}
?>
