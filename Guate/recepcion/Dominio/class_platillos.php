<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dplatillos.php');

class platillos{
		private $pla;
		private $prenormal;
		private $prelimitado;
		private $plaid;
		private $plainfo;
		private $prenormalinfo;
		private $prelimitadoinfo;
		private $cocina;
		private $plaidinfo;
		private $plfamily;
		private $plcolorfamily;
		private $idPlatillo;
		//error Message_Box
		public static $ID=4;
		public static $OK=1;
		public static $ERR_RES=-1;
		public static $ERR_CHECK=-2;
		public static $ERR=-3;
		
		
		function get_platillos($familia){
			$dtp = new DPlatillos();
			$rs = $dtp->get_platillos($familia);
			
			$this->pla=null;
			$this->prenormal=null;
			$this->prelimitado=null;
			$this->plaid=null;
			
		  if ($rs->getRecordCount()>0){
			$n=0;
			while($rs->next()){
				$result=$rs->getRow();
				$this->plaid[$n]=$result["idPlatillo"];
				$this->pla[$n]=$result["nombre"];
				$this->prenormal[$n]=$result["precioNormal"];
				$this->prelimitado[$n]=$result["precioLimitado"];
				$n++;
				}
																		
		  }else{
				$result=null;
			}
			return $rs->getRecordCount();
						
		}
		function get_info_platos(){
			$dtp = new DPlatillos();
			$rs = $dtp->get_info_platos();
			
			$this->plainfo=null;
			$this->prenormalinfo=null;
			$this->prelimitadoinfo=null;
			$this->plaidinfo=null;
			$this->cocina=null;
			$this->plfamily=null;
			$this->plcolorfamily=null;
			
			
		  if ($rs->getRecordCount()>0){
			$n=0;
			while($rs->next()){
				$result=$rs->getRow();
				$this->plaidinfo[$n]=$result["idPlatillo"];
				$this->plainfo[$n]=$result["nombre"];
				$this->prenormalinfo[$n]=$result["precioNormal"];
				$this->prelimitadoinfo[$n]=$result["precioLimitado"];
				$this->cocina[$n]=$result["cocina"];
				$this->plfamily[$n]=$result["familia"];
				$this->plcolorfamily[$n]=$result["color"];
				$n++;
				}
																		
		  }else{
				$result=null;
			}
			return $rs->getRecordCount();
						
		}
		function is_family_free($idfamilia){
			$ffr = new DPlatillos();
			$rs = $ffr->is_family_free($idfamilia);
			
		  if ($rs->getRecordCount()>0){
		  return $rs->getRecordCount();															
		  }else{$result=null;}					
		}
		
		function delete_platillo($idPlatillo){
		$dlpl = new Dplatillos();
		$rs = $dlpl->delete_platillo($idPlatillo);
		
		return $rs;
		}
		function insert_platillo($idPlato,$nameplato,$precioN,$precioL,$cocina,$platoFamily){
		$inpl = new Dplatillos();
		//echo($cocina);
		$rs = $inpl->insert_platillo($idPlato,$nameplato,$precioN,$precioL,$cocina,$platoFamily);
		
		return $rs;
		}
		function modificar_platillo($precioLmod,$precioNmod,$cocina,$idplatomod){
		$mdpl = new Dplatillos();
		$rs = $mdpl->modificar_platillo($precioLmod,$precioNmod,$cocina,$idplatomod);
		
		return $rs;
		}
		
		function get_pla($familia){
		$a=$this->pla;
		return $a; 
	   }
	function get_plaid($familia){
		$a=$this->plaid;
		return $a; 
	    }
	    function get_plaPrecioNormal($familia){
		$a=$this->prenormal;
	return $a; 
	   }
	   function get_plaPrecioLimitado($familia){
		$a=$this->prelimitado;
	return $a; 
	   }
	   function get_plainfo(){
		$a=$this->plainfo;
		return $a; 
	   }
	function get_plaidinfo(){
		$a=$this->plaidinfo;
		return $a; 
	    }
	     function get_plaPrecioNormalinfo(){
		$a=$this->prenormalinfo;
	return $a; 
	   }
	   function get_cocina(){
		$a=$this->cocina;
	return $a; 
	   }
	    function get_plaPrecioLimitadoinfo(){
		$a=$this->prelimitadoinfo;
	return $a; 
	   }
	   function get_plfamily(){
		$a=$this->plfamily;
	return $a; 
	   }
	   function get_plcolorfamily(){
		$a=$this->plcolorfamily;
	return $a; 
	   }
}
?>
