<?php
class CajaMovSuma{
    var $entrada;
    var $salida;
   
	function CajaMovSuma(){
	$this->entrada = "0";
	$this->salida = "0";
	}
	function setEntrada($entrada){
		$this->entrada=$entrada;
	}
	function setSalida($salida){
		$this->salida=$salida;
	}
	
}
?>
