<?php
class TopPlatillosVendidos{
    var $nombre;
    var $venta;
    var $cortesia;
    var $gratis;
	
	function TopPlatillosVendidos($nombre,$freq, $tipoCliente, $freqTotal){
	$this->nombre = $nombre;
	$this->freq = $freq;
	$this->tipoCliente = $tipoCliente;
	$this->freqTotal = $freqTotal;
	}	
}
?>