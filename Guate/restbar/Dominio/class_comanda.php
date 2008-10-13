<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dcomanda.php');
require ('ComandaRestore.php');
require ('LiniaComandaRestore.php');

class comanda{
		private $pla;
		private $prenormal;
		private $plaid;
		//error Message_Box
		public static $ID=4;
		public static $OK=1;
		public static $ERR_RES=-1;
		public static $ERR_CHECK=-2;
		public static $ERR=-3;
		
		//idComanda,estado,fechaHora,usuario,efectivo,mesa,tipoCliente,Total
		function setComanda($comandaID, $efectivo,$numMesa, $tipoCliente, $total, $idcliente ,$free){
			$dtp = new DComanda();
			$rs = $dtp->set_comanda($comandaID, $efectivo,$numMesa, $tipoCliente, $total, $idcliente ,$free);
			return $rs;
		}
		function setLineaComanda($comandaID,$platoId,$cantidad,$precio){
			$dtp = new DComanda();
			$rs = $dtp->setLineaComanda($comandaID,$platoId,$cantidad,$precio);
			if ($dtp->esCocina($platoId)) 
			   $dtp->setCocina($rs);
		}
		function setComandaCreditoComida($idComanda,$procedencia){
			$dtp = new DComanda();
			$rs = $dtp->setComandaCreditoComida($idComanda,$procedencia);
			return $rs;
		}
		function getNextMaxIdComanda(){
			$dtp = new DComanda();
			$rs = $dtp->getNextMaxIdComanda();
			if($rs==null) $rs = "R0";
			$number=(int)substr($rs,1);
			return "R".($number+1);
		}
}
?>