<?php
class BebidasBar{
	var $idBebida;
	var $numBebida;
    var $nombre;
    var $precioLimitado;
    var $precioNormal;
    var $color;
	var $familia;
	
	function BebidasBar($idBebida,$numBebida,$nombre,$precioLimitado,$precioNormal,$color,$familia){
	$this->idBebida = $idBebida;
	$this->numBebida = $numBebida;
	$this->nombre = $nombre;
    $this->precioLimitado = $precioLimitado;
    $this->precioNormal = $precioNormal;
	$this->color = $color;
	$this->familia = $familia;
	}
}
?>