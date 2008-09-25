<?php
class CajaMovimiento{
    var $fechaHora;
    var $tipo;
    var $dinero;
    var $descripcion;
    var $categoria;
    var $encargado;
   
	function CajaMovimiento($fechaHora,$tipo,$dinero,$descripcion,$categoria,$encargado){
	$this->fechaHora = $fechaHora;
	$this->tipo = $tipo;
	$this->dinero = $dinero;
	$this->descripcion = $descripcion;
	$this->categoria = $categoria;
	$this->encargado = $encargado;
	}
}
?>
