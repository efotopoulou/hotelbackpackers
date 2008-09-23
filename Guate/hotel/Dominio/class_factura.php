<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dfra.php');

class factura{
		private $fra;
		private $lineas;
	
		public static $ID=5;
		
		public static $OK=1;
		public static $ERR_NUMFRA=-1;
		public static $ERR=-2;
	

	function get_factura($id_fra){
		$dtcl = new Dfra();
		$rs = $dtcl->get_fra_by_id($id_fra);
		
		$this->fra=null;
		if ($rs->getRecordCount()>0){
		$rs->next();
			$resultat=$rs->getRow();
			$this->fra[0]=$resultat;
		}
		return $rs->getRecordCount();
	}

	function get_facturas_abiertas(){
		$dtcl = new Dfra();
		$rs = $dtcl->get_fras_opened();
		
		$this->fra=null;
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$resultat=$rs->getRow();
				$this->fra[$rs->getInt('Id_fra')]=$resultat;
			}
		}
		return $rs->getRecordCount();
	}
	
	function insert_factura($Idcli,$valor,$pagado,$fecfra,$total,$nombre,$impuesto,$nit,$numfra){
	
		$datos = new Dfra();
		$idfra=$datos->insert_fra ($Idcli,$valor,$pagado,$fecfra,$total,$nombre,$impuesto,$nit,$numfra);
		return $idfra;
	}
	
	function modif_fra($idfra,$total){
		$datos = new Dfra();
		$res=$datos->modif_factura($idfra,$total);
		return $res;
	}
	
	function insert_linea($Idfra,$cantidad,$descripcion,$valor,$recargo,$descuento, $Id_checkin){
		$datos = new Dfra();
		$res = $this->get_factura($Idfra);
		if($res>0){	
			$numlineas=$this->fra[0]['num_lineas'];
			$numlineas=$numlineas+1;
		}
		$result=$datos->insert_linea($Idfra,$numlineas,$cantidad,$descripcion,$valor,$recargo,$descuento,$Id_checkin);
		$datos->add_linea_fra($Idfra);
						
		return $result;
	}
	
	function cerrar_fra($nombre,$nit,$numfra,$impuesto,$total,$pendiente,$idfra,$fecha){
		$datos = new Dfra();
			
		$date = explode("/",$fecha);
		$fecha=mktime(0,0,0,$date[1],$date[0],$date[2]);	
		$res=$datos->cerrar_factura($nombre,$nit,$numfra,$impuesto,$total,$pendiente,$fecha,$idfra);
		return $res;
	}
	
	
	function actualizar_pagado($pagado,$idfra){
		$datos = new Dfra();
		$res=$datos->actualizar_pagado($pagado,$idfra);
		return $res;
	}
	
	function modificar_linea($cantidad,$descripcion,$valor,$recargo,$descuento,$idcheckin,$idfra,$idlinea){
		$datos = new Dfra();
		$res = $datos->modif_linea($cantidad,$descripcion,$valor,$recargo,$descuento,$idcheckin,$idfra,$idlinea);
		
		return $res;
	}
	
		
	function eliminar_linea($idfra,$idlinea){
		$datos = new Dfra();
		
		$res = $datos->elim_linea($idfra,$idlinea);
		
		return $res;
	}
	
	function get_lineas($Idfra){
		$datos=new Dfra();
		$res = $datos->get_lineas($Idfra);
	
		if($res->getRecordCount()>0){
			$res->next();
			do{
			$resultat=$res->getRow();
			$this->lineas[$res->getInt('Id_linea')]=$resultat;
			}while($res->next());
			
		}
		return $res->getRecordCount();
	}
	
	function get_linea($Idfra, $Idlinea){
		$datos=new Dfra();
		$res = $datos->get_linea($Idfra, $Idlinea);
	
		if($res->getRecordCount()>0){
			$res->next();
			$resultat=$res->getRow();
			$this->lineas[$res->getInt('Id_linea')]=$resultat;
		}
		return $res->getRecordCount();
	}
	
	function fra_movenext(){
		return next($this->fra);		
	}
	
	function fra_get_count(){
		return count($this->fra);
	}
	
	function movenext(){
		return next($this->lineas);		
	}
	
	function get_count(){
		return count($this->lineas);
	}
	
	function get_id_linea(){
		$a=current($this->lineas);
		return $a['Id_linea']; 
	}
	function get_noches(){
		$a=current($this->lineas);
		return $a['cantidad']; 
	}
	function get_descripcion(){
		$a=current($this->lineas);
		return $a['descripcion']; 
	}
	function get_importe(){
		$a=current($this->lineas);
		return $a['valor']; 
	}
	
	function get_pagado(){
		$a=current($this->fra);
		return $a['importe_pagado']; 
	}
	
	function get_recargo(){
		$a=current($this->lineas);
		return $a['recargo']; 
	}
	
	function get_descuento(){
		$a=current($this->lineas);
		return $a['descuento']; 
	}
	
	function get_id_checkin(){
		$a=current($this->lineas);
		return $a['Id_checkin']; 
	}
/*	function get_impuesto(){
		$a=current($this->lineas);
		return $a['impuesto']; 
	}
*/	
	function get_nombre(){
		$a=current($this->fra);
		return $a['nombre'];
	}
	
	function get_fechafra(){
		$a=current($this->fra);
		return $a['fecha_fra']; 
	}
	
	function get_id_fra(){
		$a=current($this->fra);
		return $a['Id_fra']; 
	}
	
	function get_nit(){
		$a=current($this->fra);
		return $a['nit']; 
	}

}
		
?>