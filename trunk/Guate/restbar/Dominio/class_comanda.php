<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Datos/Dcomanda.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/ComandaRestore.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/LiniaComandaRestore.php');

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
			//if ($dtp->esCocina($platoId)) 
			//   $dtp->setCocina($rs);
		}
		function esCocina($platoId){
			$dtp = new DComanda();
			$rs = $dtp->esCocina($platoId);
			return $rs;
		}
		function setCocina($comandaID,$platoId, $cantidad){
			$dtp = new DComanda();
			$dtp->setCocina($comandaID,$platoId, $cantidad);
		}
		function setLineaComandaBebida($comandaID,$platoId,$cantidad,$precio){
			$dtp = new DComanda();
			$rs = $dtp->setLineaComanda($comandaID,$platoId,$cantidad,$precio);
		}

		function setComandaCreditoComida($idComanda,$procedencia){
			$dtp = new DComanda();
			$rs = $dtp->setComandaCreditoComida($idComanda,$procedencia);
			return $rs;
		}
		function getNextMaxIdComanda(){
			$dtp = new DComanda();
			$rs = $dtp->getNextMaxIdComanda();
			if($rs==null) $rs = "0";
			//$number=(int)substr($rs,1);
			return $rs+1;
		}
		function esPlatillo($platoId){
			$dtp = new DComanda();
			$rs = $dtp->esPlatillo($platoId);
			return ($rs->getRecordCount()>0);
		}





		function borrarComanda($estado){
			$dtp = new DComanda();
			$rs = $dtp->borrarComanda($estado);
		}
		
		function setComandaVenta($efectivo, $tipoCliente, $total, $idcliente ,$free){
			$dtp = new DComanda();
			$rs = $dtp->setComandaVenta($efectivo, $tipoCliente, $total, $idcliente ,$free);
			return $rs;
		}
		function setComandaCredito($idComanda,$procedencia){
			$dtp = new DComanda();
			$rs = $dtp->setComandaCredito($idComanda,$procedencia);
			return $rs;
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
		function setLineaComandaNoCocina($comandaID,$platoId,$cantidad,$precio){
			$dtp = new DComanda();
			$rs = $dtp->setLineaComanda($comandaID,$platoId,$cantidad,$precio);
		}
		function updateComandaAbierta($comandaID,$efectivo){
			$dtp = new DComanda();
			$rs = $dtp->updateComandaAbierta($comandaID,$efectivo);
			
		}
		function existeIdComanda($idComanda){
			$dtp = new DComanda();
			return $dtp->existeIdComanda($idComanda);
		}
}
?>