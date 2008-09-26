<?php
class StockReception{
	var $nombre;
	var $idBebida;
	var $precioNormal;
    var $precioLimitado;
	
	function StockReception($nombre,$idBebida,$precioNormal, $precioLimitado){
	$this->nombre = $nombre;
	$this->idBebida = $idBebida;
	$this->precioNormal = $precioNormal;
	$this->precioLimitado = $precioLimitado;
	}
}
?>
