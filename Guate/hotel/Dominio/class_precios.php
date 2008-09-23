<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dprecios.php');

class precios{
	private $precios;
	private $tprecios;
	
	public static $TIPO_NORMAL=1;
	public static $TIPO_ALTO=2;
	
	public static $ID=8;
	public static $OK=1;
	public static $ERR=-1;
	public static $ERR_NO_TEMP=-2;
	
	
	function __construct(){
					
	}
	
	function get_all_tipos(){
		$datos = new Dprecios();
		
		$rs = $datos->get_tipos();	
		
		$this->tprecios=null;		
		while($rs->next()) {
			$this->tprecios[$rs->getInt('Id_tipo')] = array("nombre"=>$rs->getString('nombre'));	
		}
		return $rs->getRecordCount();
	}
	
	function get_precios($id_tipo){
		$datos = new Dprecios();
		
		$rs = $datos->get_precios_all($id_tipo);	
		
		$this->precios=array();		
		while($rs->next()) {
			$this->precios[$rs->getInt('Id_aloj')] = $rs->getInt('precio');	
		}
		return $rs->getRecordCount();
	}

	function insertar_precios($data, $id_tprecio){
		$datos = new Dprecios();
	
		$res=$this->get_precios($id_tprecio);
		
		foreach($data as $key => $precio){
			if(is_numeric($precio)){
				$key=split("_",$key);
				$id_aloj=$key[1];
		
				if(array_key_exists($id_aloj,$this->precios)){
					$res=$datos->update_precio($precio, $id_aloj, $id_tprecio);
				}	
				else{
					$res=$datos->insert_precio($precio, $id_aloj, $id_tprecio);
				}					
			}
		}
					
	}
	
	function get_id(){
		return key($this->precios);		
	}
	
	function get_precio($id_aloj=0){
		if($id_aloj>0)
			return $this->precios[$id_aloj];
		else
			return current($this->precios);		
	}

	function get_count(){
		return count($this->precios);
	}
	
	function movenext(){
		return next($this->precios);		
	}	
	
	function movefirst(){
		reset($this->precios);
	}
	
	//tipos de precio
	
	function t_get_count(){
		return count($this->tprecios);
	}
	
	function t_movenext(){
		return next($this->tprecios);		
	}
	
	function t_get_id(){
		return key($this->tprecios);		
	}
	
	function t_get_nombre($id=0){
		if($id)
			$a=$this->tprecios[$id];
		else
			$a=current($this->tprecios);
		return $a["nombre"];		
	}
	
	function t_es_tipo_normal(){
		return (key($this->tprecios) == precios::$TIPO_NORMAL );			
	}
}