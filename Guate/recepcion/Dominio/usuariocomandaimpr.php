<?php
class UsuarioComandaImpr{
    var $idComanda;
    var $numComanda;
    var $procedencia;
    var $fechaHora;
    var $total;
    var $nombre;
	
	function UsuarioComandaImpr($numComanda,$fechaHora,$idLineaComanda,$total,$nombre,$cantidad){
	$this->numComanda = $numComanda;
	$this->fechaHora = $fechaHora;
	$this->idLineaComanda = $idLineaComanda;
	$this->total = $total;
	$this->nombre = $nombre;
	$this->cantidad= $cantidad;
	}
}
?>
