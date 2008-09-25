<?php
class ReportDetail{
    var $id_categoria;
    var $date;
    var $time;
    var $descripcion;
    var $entrada;
    var $salida;
    var $categoria;
	
	function ReportDetail($id_categoria,$date,$time,$descripcion,$entrada,$salida,$categoria){
	$this->id_categoria = $id_categoria;
	$this->date = $date;
	$this->time = $time;
	$this->descripcion = $descripcion;
	$this->entrada = $entrada;
	$this->salida = $salida;
	$this->categoria = $categoria;
	}
}
?>
