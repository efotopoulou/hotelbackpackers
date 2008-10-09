<?php
class CajaMovimiento{
    var $id_movimiento;
    var $fechaHora;
    var $tipo;
    var $dinero;
    var $descripcion;
    var $categoria;
    var $encargado;
   
	function CajaMovimiento($id_movimiento,$fechaHora,$tipo,$dinero,$descripcion,$categoria,$encargado){
	$this->id_movimiento = $id_movimiento;
	$this->fechaHora = $fechaHora;
	$this->tipo = $tipo;
	$this->dinero = $dinero;
	$this->descripcion = $descripcion;
	$this->categoria = $categoria;
	$this->encargado = $encargado;
	}
}
?>
