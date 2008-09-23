<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dcaja.php');


class caja{
	
		public static $CONTADO=1;
		public static $VISA=2;
		private $caja;
		
		
		function get_caja($idcaja){
			$dtcl = new Dcaja();
			$rs = $dtcl->get_caja_by_id($idcaja);
			
			$this->caja=null;
			if ($rs->getRecordCount()>0){
			$rs->next();
				$resultat=$rs->getRow();
				$this->caja[0] = $resultat;
			}
			return $rs->getRecordCount();
		}
		
		function insert_movimiento($importe,$modopago,$id_checkin,$id_factura,$descripcion){
			if($importe!=0){
				$datos = new Dcaja();
				$rs = $datos->insert_movimiento($importe,$modopago,$id_checkin,$id_factura,$descripcion);
			}
			return $rs;
		}
		
		function get_by_dates($fecha1,$fecha2){
			
			$datos = new Dcaja();
			$res = $datos->get_caja_by_dates($fecha1,$fecha2);
			$i=0;
			if($res>0){
				if($res->getRecordCount()>0){
					$res->next();
					do{
					$resultat=$res->getRow();
					$this->caja[$i]=$resultat;
					$i++;
					}while($res->next());					
					return $res->getRecordCount();	
				}
			}
			return 0;
		}
			
	function movenext(){
		return next($this->caja);		
	}
	
	function get_count(){
		return count($this->caja);
	}
	
	function get_id_linea(){
		$a=current($this->caja);
		return $a['Id_movimiento'];
	}
	
	function get_entrada(){
		$a=current($this->caja);
		return $a['entrada']; 
	}
	
	function get_salida(){
		$a=current($this->caja);
		return $a['salida']; 
	}
	
	function get_descripcion(){
		$a=current($this->caja);
		return $a['descripcion']; 
	}	









}

?>