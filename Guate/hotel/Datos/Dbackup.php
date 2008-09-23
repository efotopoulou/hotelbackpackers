<?php

require_once ('Comunication.php');

class Dbackup{

	const MAKE_BACKUP = "SELECT * INTO OUTFILE '?' FROM ?";
	
	const GET_CLIENTE = 'SELECT * FROM cliente
			LEFT JOIN pais ON cliente.Id_pais=pais.Id_pais 
			WHERE cliente.Id_cliente=?';
	
	public function insert_Client ($pasaporte,$nombre,$apellido1,$apellido2,$direccion,$Id_pais,$telefono1,$telefono2,$email,$observaciones,$poblacion){
		$comunication = new Comunication();
		$id="";
		$params = array(0,$pasaporte,$nombre,$apellido1,$apellido2,$direccion,$Id_pais,$telefono1,$telefono2,$email,$observaciones,$poblacion);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TINT,Comunication::$TINT,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING);
		$result = $comunication->update(self::ALT_CLIENT,$params,$PARAMS_INSERT,$id);
		return $id;     //retorna id del cliente
	}		
	
			
	public function make_backup ($file, $table){
		$comunication = new Comunication();
		$PARAMS = array ($file, $table);		
		$PARAMS_TYPES = array (Comunication::$TSTRING,Comunication::$TSTRING);
		$result = $comunication->query(self::MAKE_BACKUP,$PARAMS,$PARAMS_TYPES);
		return $result;	
	}
	

}	
?>