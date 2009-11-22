<?php
class TopPlatillosVendidos{
    var $nombre;
    var $venta;
    var $cortesia;
    var $gratis;
	
	function TopPlatillosVendidos($nombre,$venta, $cortesia, $gratis){
	$this->nombre = $nombre;
	$this->venta = $venta;
	$this->cortesia = $cortesia;
	$this->gratis = $gratis;
	}	
}
?>