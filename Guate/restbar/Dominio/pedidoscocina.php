<?php
class PedidosCocina{
    var $idCocina;
    var $numComanda;
    var $idPlatillo;
    var $cantidad;
    var $nombre;
    var $hora;
	
	function PedidosCocina($idCocina,$numComanda,$idPlatillo,$nombre,$cantidad,$hora){
	$this->idCocina = $idCocina;
	$this->numComanda = $numComanda;
	$this->idPlatillo = $idPlatillo;
	$this->nombre= $nombre;
	$this->cantidad = $cantidad;
	$this->hora = $hora;
	}
  
	}
?>
