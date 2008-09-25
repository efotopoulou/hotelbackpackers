<?php
class StockBar{
	var $idBebida;
	var $numBebida;
    var $nombre;
    var $stockbar;
    var $stockrestaurante;
    var $unidadventa;
	
	function StockBar($idBebida,$numBebida,$nombre,$stockbar,$stockrestaurante,$unidadventa){
	$this->idBebida = $idBebida;
	$this->numBebida = $numBebida;
	$this->nombre = $nombre;
    $this->stockbar = $stockbar;
    $this->stockrestaurante = $stockrestaurante;
    $this->unidadventa = $unidadventa;
	}
}
?>
