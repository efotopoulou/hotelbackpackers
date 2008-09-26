<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dbebidas.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/bebidasbar.php');

class bebidas{
		private $pla;
		private $prenormal;
		private $prelimitado;
		private $plaid;
		private $plainfo;
		private $prenormalinfo;
		private $prelimitadoinfo;
		//private $cocina;
		private $plaidinfo;
		private $plfamily;
		private $plcolorfamily;
		private $idPlatillo;
		
		
		function get_bebidas($familia){
			$dtp = new Dbebidas();
			$rs = $dtp->get_bebidas($familia);
			
			$this->pla=null;
			$this->prenormal=null;
			$this->prelimitado=null;
			$this->plaid=null;
		  if ($rs->getRecordCount()>0){
			$n=0;
			while($rs->next()){
				$result=$rs->getRow();
				$this->plaid[$n]=$result["idBebida"];
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
		
        function get_info_bebidas(){
        $dtp = new Dbebidas();
        $rs = $dtp->get_info_bebidas();
			
          if ($rs->getRecordCount()>0){
	       $n=0;
	     while($rs->next()){
		$result=$rs->getRow();
		$fam = new BebidasBar($result["idBebida"],$result["numBebida"],$result["nombre"],$result["precioLimitado"],$result["precioNormal"],$result["color"],$result["familia"]);
		$a[$n]=$fam;
		$n++;
		}														
        }else{
	    $result=null;
			}
        return $a;				
		}
		
		function is_family_free($idfamilia){
			$ffr = new Dbebidas();
			$rs = $ffr->is_family_free($idfamilia);
			
		  if ($rs->getRecordCount()>0){
		  return $rs->getRecordCount();															
		  }else{$result=null;}					
		}
		
		function delete_bebida($idbebida){
		$dlpl = new Dbebidas();
		$rs = $dlpl->delete_bebida($idbebida);
		
		return $rs;
		}
		function insert_bebida($idbebida,$namebebida,$precioL,$precioN,$bebidaFamily){
		$inpl = new Dbebidas();
		$rs = $inpl->insert_bebida($idbebida,$namebebida,$precioL,$precioN,$bebidaFamily);
		
		return $rs;
		}
		function modificar_bebida($precioLmod,$precioNmod,$idbebida){
		$mdpl = new Dbebidas();
		$rs = $mdpl->modificar_bebida($precioLmod,$precioNmod,$idbebida);
		
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
