<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Datos/Destadisticas.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/cmweek.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/cmmonth.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/cmyear.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/topPlatillos.php');

class estadisticas{
		
		function yearsCaja(){
			$gy = new Destadisticas();
			$rs = $gy->get_year();
		    $i=0;
		if($rs>0){
				if($rs->getRecordCount()>0){
					$rs->next();
					do{
					$result=$rs->getRow();
					$anyo[$i]=$result["anyo"];
					$i++;
					}while($rs->next());					
				}
			}
			return $anyo;
		}
		function caja_movimientos_year($currentyear){
		$cmy = new Destadisticas();
		$rs = $cmy->caja_movimientos_year($currentyear);
			
		  if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$ors[$result["mes"]] = new CmYear($result["mes"],$result["suma"],$result["entradas"],$result["salidas"]);
				}														
		  }else{
				$result=null;
			}
			return $ors;				
		}
		
		function caja_month($currentyear,$currentmes){
		$cmm = new Destadisticas();
		$rs = $cmm->caja_month($currentyear,$currentmes);
		
		  if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$ors[$result["dia"]] = new CmMonth($result["dia"],$result["suma"],$result["entradas"],$result["salidas"]);
				//$diasuma[$result["dia"]] =$result["suma"];
				}														
		  }else{
				$result=null;
			}
			return $ors;				
		}
		
		function caja_movimientos_week($year,$date){
		$cmw = new Destadisticas();
		$rs = $cmw->caja_movimientos_week($year,$date);
		 if ($rs->getRecordCount()>0){
		while($rs->next()){
			$result=$rs->getRow();
			$ors[$result["fecha"]][$result["id_caja"]] = new CmWeek($result["id_caja"],$result["suma"],$result["entradas"],$result["salidas"],$result["fechaHoraApertura"],$result["numday"],$result["fecha"],$result["mes"],$result["anyo"]);
			}														
		  }else{
				$result=null;
			}
			return $ors;				
		}
		
		function topPlatillosWeek($tipoEstadistica,$year,$month,$date,$limit){
		$tpw = new Destadisticas();
		$rs = $tpw->topPlatillosWeek($tipoEstadistica,$year,$month,$date,$limit);
		
		  if ($rs->getRecordCount()>0){
			$n=1;
			while($rs->next()){
				$result=$rs->getRow();
				$ors[$n] = new TopPlatillos($result["nombre"],$result["freq"]);
				//$diasuma[$result["dia"]] =$result["suma"];
				$n++;
				}														
		  }else{
				$result=null;
			}
			return $ors;		
		}
		function topPlatillosMonth($tipoEstadistica,$year,$month,$limit){
		$tpm = new Destadisticas();
		$rs = $tpm->topPlatillosMonth($tipoEstadistica,$year,$month,$limit);
		
		  if ($rs->getRecordCount()>0){
			$n=1;
			while($rs->next()){
				$result=$rs->getRow();
				$ors[$n] = new TopPlatillos($result["nombre"],$result["freq"]);
				//$diasuma[$result["dia"]] =$result["suma"];
				$n++;
				}														
		  }else{
				$result=null;
			}
			return $ors;		
		}
		function topPlatillosYear($tipoEstadistica,$year,$limit){
		$tpy = new Destadisticas();
		$rs = $tpy->topPlatillosYear($tipoEstadistica,$year,$limit);
		
		  if ($rs->getRecordCount()>0){
			$n=1;
			while($rs->next()){
				$result=$rs->getRow();
				$ors[$n] = new TopPlatillos($result["nombre"],$result["freq"]);
				//$diasuma[$result["dia"]] =$result["suma"];
				$n++;
				}														
		  }else{
				$result=null;
			}
			return $ors;	
		}
		
		
		function open_caja($fondo){
		$opcj = new Dcaja();
		$rs = $opcj->open_caja($fondo);
		
		return $rs;
		}
		
		function get_fondo_caja(){
			$fcj = new Dcaja();
			$rs = $fcj->get_fondo_caja();
			
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["fondoInicial"];
				}
																		
		  }else{
				$result=null;
			}
			return $a;
		}
		
		function close_caja($efectivoCerrar){
		$clcj = new Dcaja();
		$rs = $clcj->close_caja($efectivoCerrar);
		
		return $rs;
		}
		function cobrar_ticket($idComanda){
		$cbtc = new Dcaja();
		$rs = $cbtc->cobrar_ticket($idComanda);
		
		return $rs;
		}
		function anular_ticket($idComanda){
		$antc = new Dcaja();
		$rs = $antc->anular_ticket($idComanda);
		
		return $rs;
		}
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
		
		function insert_movimiento($tipo,$dinero,$descripcion){
			$datos = new Dcaja();
			$rs = $datos->insert_movimiento($tipo,$dinero,$descripcion);
		}
		
		function total_mov(){
			$mvcj = new Dcaja();
			$rs = $mvcj->total_money_mov();
		    $i=0;
		if($rs>0){
				if($rs->getRecordCount()>0){
					$rs->next();
					do{
					$result=$rs->getRow();
					$this->totalTipo[$i]=$result["tipo"];
					$this->totalmov[$i]=$result["suma"];
					$i++;
					}while($rs->next());					
					return $rs->getRecordCount();	
				}
			}
			return 0;
		}
		
		function total_tickets(){
			$fcj = new Dcaja();
			$rs = $fcj->total_tickets ();
			
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["totalTickets"];
				}
																		
		  }else{
				$result=null;
			}
			return $a;
		}
		
		function ld_mov(){
			$ldmv = new Dcaja();
			$rs = $ldmv->load_mov();
			$this->fechaHoram=null;
			$this->tipo=null;
			$this->dinero=null;
			$this->description=null;
		    $i=0;
		if($rs>0){
				if($rs->getRecordCount()>0){
					$rs->next();
					do{
					$result=$rs->getRow();
					$this->fechaHoram[$i]=$result["fechaHora"];
					$this->tipo[$i]=$result["tipo"];
					$this->dinero[$i]=$result["dinero"];
					$this->description[$i]=$result["descripcion"];
					$i++;
					}while($rs->next());					
					return $rs->getRecordCount();	
				}
			}
			return 0;
		}
		function ld_tickets(){
			$ldtck = new Dcaja();
			$rs = $ldtck->load_tickets();
			$this->fechaHorat=null;
			$this->idComanda=null;
			$this->estado=null;
			$this->total=null;
			$this->efectivo=null;
			$this->cambio=null;
			$this->tipoCliente=null;
			$this->nombre=null;
		    $i=0;
		if($rs>0){
				if($rs->getRecordCount()>0){
					$rs->next();
					do{
					$result=$rs->getRow();
					$this->fechaHorat[$i]=$result["fechaHora"];
					$this->idComanda[$i]=$result["idComanda"];
					$this->estado[$i]=$result["estado"];
					$this->total[$i]=$result["total"];
					$this->efectivo[$i]=$result["efectivo"];
					$this->tipoCliente[$i]=$result["tipoCliente"];
					$this->nombre[$i]=$result["nombre"];
					$i++;
					}while($rs->next());					
					return $rs->getRecordCount();	
				}
			}
			return 0;
		}

		
	function get_entrada(){
	for($i=0;$i<count($this->totalTipo);$i++){
		if ($this->totalTipo[$i]=="entrada") $result=$this->totalmov[$i];
	}
	return $result; 
	}
	function get_salida(){
	for($i=0;$i<count($this->totalTipo);$i++){
		if ($this->totalTipo[$i]=="salida") $result=$this->totalmov[$i];
	}
	return $result; 
	}
	function get_fechaHoram(){
	  $a=$this->fechaHoram;
	return $a; 
	   }
	   
	function get_tipo(){
      $a=$this->tipo;
	return $a; 
	   }


}

?>
