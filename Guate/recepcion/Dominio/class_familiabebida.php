<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dfamiliabebida.php');
require ('familia.php');
 
class class_familia{
		private $nombre;
		private $family;
		private $idfamily;
		private $colorfamily;
		//error Message_Box
		public static $ID=3;
		
		public static $OK=1;
		public static $ERR_RES=-1;
		public static $ERR_CHECK=-2;
		public static $ERR=-3;

		function getNombre()
		{
			return $this->nombre;
		}
			
	
		function delete_family($idfamily){
		$dlfm = new Dfamilia();
		$rs = $dlfm->delete_family($idfamily);
		
		return $rs;
		}
		
		function insert_family($nombre,$color){
		$infm = new Dfamilia();
		$rs = $infm->insert_family($nombre,$color);
		
		return $rs;
		}
		function modificar_family($namefamilymod,$famidmod){
		$mdfm = new Dfamilia();
		$rs = $mdfm->modificar_family($namefamilymod,$famidmod);
		}
		
	   function get_family(){
		$this->get_familias();
		$a=$this->family;
		return $a; 
	   }
	   
	   function get_familias(){
			$dtf = new Dfamilia();
			$rs = $dtf->get_familias();
			
			$this->family=null;
			$this->idfamily=null;
			$this->colorfamily=null;
			
		  if ($rs->getRecordCount()>0){
			$n=0;
			while($rs->next()){
				$result=$rs->getRow();
				$this->idfamily[$n]=$result["id_familia"];
				$this->family[$n]=$result["nombre"];
				$this->colorfamily[$n]=$result["color"];
				$n++;
				}														
		  }else{
				$result=null;
			}
			return $rs->getRecordCount();
						
		}
	   function get_families(){
			$dtf = new Dfamilia();
			$rs = $dtf->get_familias();

		  if ($rs->getRecordCount()>0){
			$n=0;
			while($rs->next()){
				$result=$rs->getRow();
				$fam = new Familia($result["id_familia"],$result["nombre"],$result["color"]);
				$a[$n]=$fam;
				$n++;
				}														
		  }else{
				$result=null;
			}
		return $a; 
	   }
	   
	   function get_idfamily(){
       $a=$this->idfamily;
	   return $a; 
	    }
	    function get_colorfamily(){
       $a=$this->colorfamily;
	   return $a; 
	    }
} 
?>
