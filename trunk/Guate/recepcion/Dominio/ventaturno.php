<?php
class VentaTurno{
	var $numBebida;
	var $nombre;
	var $suma;
	var $clientType;
	
	function VentaTurno($numBebida,$nombre,$suma,$clientType){
	$this->numBebida = $numBebida;
	$this->nombre = $nombre;
	$this->suma = $suma;
	$this->clientType = $clientType;
	}
}
?>
