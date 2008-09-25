<?php
class PedidosCocina{
    var $idLineaComanda;
    var $idComanda;
    var $idPlatillo;
    var $nombre;
    var $fondoInicial;
    var $hora;
	
	function PedidosCocina($idLineaComanda,$idComanda,$idPlatillo,$nombre,$cantidad,$hora){
	$this->idLineaComanda = $idLineaComanda;
	$this->idComanda = $idComanda;
	$this->idPlatillo = $idPlatillo;
	$this->nombre= $nombre;
	$this->cantidad = $cantidad;
	$this->hora = $hora;
	}
  
	}
?>

