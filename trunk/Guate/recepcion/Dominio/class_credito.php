<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dcredito.php');

class credito{
		
		function setComandaCredito($idComanda,$procedencia){
			$dtp = new DCredito();
			$rs = $dtp->setComandaCredito($idComanda,$procedencia);
			return $rs;
		}
		function setComandaCreditoComida($idComanda,$procedencia){
			$dtp = new DCredito();
			$rs = $dtp->setComandaCreditoComida($idComanda,$procedencia);
			return $rs;
		}
		function setComandaCreditoBebida($idComanda,$procedencia){
			$dtp = new DCredito();
			$rs = $dtp->setComandaCreditoBebida($idComanda,$procedencia);
			return $rs;
		}
		
}
?>
