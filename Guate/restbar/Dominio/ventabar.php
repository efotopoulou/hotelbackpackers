<?php
class VentaBar{
    var $numBebida;
    var $nombre;
    var $suma;
    var $precio;
	
	function VentaBar($numBebida,$nombre,$suma,$precio){
	$this->numBebida = $numBebida;
	$this->nombre = $nombre;
	$this->suma = $suma;
	$this->precio = $precio;
	}
}
?>
