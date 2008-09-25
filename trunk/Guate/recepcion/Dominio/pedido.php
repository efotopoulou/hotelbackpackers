<?php
class Pedido{
    var $idPlatillo;
    var $cantidad;
    var $nombre;
    var $precio;
	
	function Pedido($idPlatillo,$cantidad,$nombre,$precio){
	$this->idPlatillo = $idPlatillo;
	$this->cantidad = $cantidad;
	$this->nombre= $nombre;
	$this->precio = $precio;
	}
  
	}
?>