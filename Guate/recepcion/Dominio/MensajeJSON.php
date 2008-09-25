<?php
class MensajeJSON{
		private $datos;
		private $mensaje;
		private $error;
		
	function setDatos($dat){
			$this->datos = $dat;
	}
	function setMensaje($men){
			$this->mensajes = $men;
	}
	function setError($err){
			$this->errores = $err;
	}
	function encode(){
			$rs["Datos"]=$this->datos;
			$rs["Mensaje"]=$this->mensajes;
			$rs["Error"]=$this->errores;
		return json_encode($rs);
	}
	
}
?>
