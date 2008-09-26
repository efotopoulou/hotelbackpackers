<?php
class LiniaComandaRestore{
    var $platoId;
    var $precioN;
    var $precioUnidad;
    var $precioNormal;
    var $precioLimitado;
    var $producto;
	
	function LiniaComandaRestore($platoId,$precioN, $precioUnidad, $precioNormal, $precioLimitado, $producto){
	$this->$platoId=$platoId;
	$this->$precioN=$precioN;
	$this->$precioUnidad=$precioUnidad;
	$this->$precioNormal=$precioNormal;
	$this->$precioLimitado=$precioLimitado;
	$this->$producto=$producto;
	}
}
?>