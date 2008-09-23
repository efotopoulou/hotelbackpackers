<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dpais.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_eventos.php');

class pais{
	private $pais;
	
	function __construct(){
					
	}
	
	function get_paises(){
		$datos = new Dpais();
	
		$rs = $datos->get_paises();
	
		$this->pais=null;
		while($rs->next()) {
			$this->pais[$rs->getInt('Id_pais')] = array("nombre"=>$rs->getString('nom_pais'));
		}
		return $rs->getRecordCount();
	}
	
	
	
	function get_nombre(){
		$a=current($this->pais);
		return $a["nombre"];		
	}
		
	function get_id(){
		return key($this->pais);		
	}
	
	function get_count(){
		return count($this->pais);
	}
	
	function movenext(){
		return next($this->pais);		
	}	
	
	function movefirst(){
		reset($this->pais);
	}	
	
	function current(){
		return current($this->pais);
	}
	
}

?>