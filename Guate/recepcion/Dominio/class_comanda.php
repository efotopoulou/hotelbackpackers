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
		function setComandaAbierta($comandaID, $efectivo,$numMesa, $tipoCliente, $total, $idcliente ,$free){
			$dtp = new DComanda();
			$rs = $dtp->set_comandaAbierta($comandaID, $efectivo,$numMesa, $tipoCliente, $total, $idcliente ,$free);
		}
		function borrarComanda($estado){
			$dtp = new DComanda();
			$rs = $dtp->borrarComanda($estado);
		}
		
		function setComanda($comandaID, $estado, $efectivo,$numMesa, $tipoCliente, $total, $idcliente ,$free){
			$dtp = new DComanda();
			$rs = $dtp->setComanda($comandaID, $estado, $efectivo,$numMesa, $tipoCliente, $total, $idcliente ,$free);
		}
		function comandasRestore(){
			$dtp = new DComanda();
			$rs = $dtp->comandasRestore();
			$result= array();
		    if ($rs->getRecordCount()>0){
			    while($rs->next()){
					$comanda=$rs->getRow();
					$liniasComanda= $this->getLiniasComanda($comanda["idComanda"]);
					$comanda["liniasComanda"]=$liniasComanda;
					//$comandaRestore = new ComandaRestore($comanda["tipoCliente"],$comanda["idComanda"],$comanda["efectivo"],$comanda["total"],$comanda["id_cliente"],$comanda["free"],$comanda["estado"],$comanda["mesa"],$liniasComanda);
					//array_push($result,$comandaRestore);
					array_push($result,$comanda);
				}
			}
			return $result;		
		}
		function getLiniasComanda($idComanda){
			$dtp = new DComanda();
			$rs = $dtp->getLiniasComanda($idComanda);
			$result= array();
		    if ($rs->getRecordCount()>0){
			    while($rs->next()){
					$liniacomanda=$rs->getRow();
					//$liniacomandaRestore = new LiniaComandaRestore($liniacomanda["platoId"],$liniacomanda["platoN"],$liniacomanda["precioUnidad"],$liniacomanda["precioNormal"],$liniacomanda["precioLimitado"],$liniacomanda["produto"]);
					//array_push($result,$liniacomandaRestore);
					array_push($result,$liniacomanda);
				}
			}
			return $result;		
		}
		function borrarLineasComanda($comandaID){
			$dtp = new DComanda();
			$rs = $dtp->borrarLineasComanda($comandaID);
		}
		function setLineaComanda($comandaID,$platoId,$cantidad,$precio){
			$dtp = new DComanda();
			$rs = $dtp->setLineaComanda($comandaID,$platoId,$cantidad,$precio);
			if ($dtp->esCocina($platoId)) 
			   $dtp->setCocina($rs);
		}
		function setLineaComandaNoCocina($comandaID,$platoId,$cantidad,$precio){
			$dtp = new DComanda();
			$rs = $dtp->setLineaComanda($comandaID,$platoId,$cantidad,$precio);
		}
		function updateComandaAbierta($comandaID,$efectivo){
			$dtp = new DComanda();
			$rs = $dtp->updateComandaAbierta($comandaID,$efectivo);
			
		}
		function getNextMaxIdComanda(){
			$dtp = new DComanda();
			$rs = $dtp->getNextMaxIdComanda();
			if($rs==null) $rs = "R0";
			$number=(int)substr($rs,1);
			return "R".($number+1);
		}
		function existeIdComanda($idComanda){
			$dtp = new DComanda();
			return $dtp->existeIdComanda($idComanda);
		}
}
?>