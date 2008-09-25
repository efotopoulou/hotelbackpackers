<?php
class StockReception{
	var $idBebida;
	var $numBebida;
    var $nombre;
	
	function StockReception($idBebida,$numBebida,$nombre){
	$this->idBebida = $idBebida;
	$this->numBebida = $numBebida;
	$this->nombre = $nombre;
	}
}
?>
