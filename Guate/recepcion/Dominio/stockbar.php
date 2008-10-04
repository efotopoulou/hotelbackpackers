<?php
class StockBar{
	var $idBebida;
	var $numBebida;
	var $familia;
    var $nombre;
    var $stockbar;
    var $stockrestaurante;
    var $unidadventa;
	
	function StockBar($idBebida,$numBebida,$familia,$nombre,$stockbar,$stockrestaurante,$unidadventa){
	$this->idBebida = $idBebida;
	$this->numBebida = $numBebida;
	$this->familia = $familia;
	$this->nombre = $nombre;
    $this->stockbar = $stockbar;
    $this->stockrestaurante = $stockrestaurante;
    $this->unidadventa = $unidadventa;
	}
}
?>
