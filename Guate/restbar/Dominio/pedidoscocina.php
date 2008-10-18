<?php
class PedidosCocina{
    var $idLineaComanda;
    var $idComanda;
    var $numComanda;
    var $idPlatillo;
    var $nombre;
    var $fondoInicial;
    var $hora;
	
	function PedidosCocina($idLineaComanda,$idComanda,$numComanda,$idPlatillo,$nombre,$cantidad,$hora){
	$this->idLineaComanda = $idLineaComanda;
	$this->idComanda = $idComanda;
	$this->numComanda = $numComanda;
	$this->idPlatillo = $idPlatillo;
	$this->nombre= $nombre;
	$this->cantidad = $cantidad;
	$this->hora = $hora;
	}
  
	}
?>
