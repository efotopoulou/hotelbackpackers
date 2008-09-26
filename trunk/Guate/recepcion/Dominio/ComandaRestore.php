<?php
class ComandaRestore{
    var $currentClientType;
    var $comandaId;
    var $efectivo;
    var $total;
    var $clienteName;
    var $free;
    var $estado;
    var $mesa;
    var $liniasComanda;
	
	function ComandaRestore($currentClientType, $comandaId, $efectivo, $total, $clienteName, $free, $estado, $mesa, $liniasComanda){
	$this->currentClientType=$currentClientType;
	$this->comandaId=$comandaId;
	$this->efectivo=$efectivo;
	$this->total=$total;
	$this->clienteName=$clienteName;
	$this->free=$free;
	$this->estado=$estado;
	$this->mesa =$mesa;
	$this->liniasComanda=$liniasComanda;
	}
}
?>