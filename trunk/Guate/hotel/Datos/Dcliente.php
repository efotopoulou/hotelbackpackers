<?php

require_once ('Comunication.php');

class Dcliente{

	const ALT_CLIENT = 'INSERT INTO cliente VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';
	
	const MODIF_CLIENT = 'UPDATE cliente SET pasaporte=?,nombre=?,apellido1=?,apellido2=?,direccion=?,Id_pais=?,telefono1=?,telefono2=?,email=?,observaciones=?,poblacion=? WHERE Id_cliente=?';
	
	const ELIM_CLIENT = 'DELETE from cliente where Id_cliente=?';
	
	const GET_CLIENTE = 'SELECT * FROM cliente
			LEFT JOIN pais ON cliente.Id_pais=pais.Id_pais 
			WHERE cliente.Id_cliente=?';
	
	public function insert_client ($pasaporte,$nombre,$apellido1,$apellido2,$direccion,$Id_pais,$telefono1,$telefono2,$email,$observaciones,$poblacion){
		$comunication = new Comunication();
		$id=0;
		$params = array(0,$pasaporte,$nombre,$apellido1,$apellido2,$direccion,$Id_pais,$telefono1,$telefono2,$email,$observaciones,$poblacion);
		$PARAMS_INSERT = array(Comunication::$TINT,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING);
		$result = $comunication->update(self::ALT_CLIENT,$params,$PARAMS_INSERT,$id);
		return $id;     //retorna id del cliente
	}		
	
			
	public function get_dat_cliente ($id_cl){
		$comunication = new Comunication();
		$PARAMS = array ($id_cl);		
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_CLIENTE,$PARAMS,$PARAMS_TYPES);
		return $result;	
	}
	
	public function modificar_client ($pasaporte,$nombre,$apellido1,$apellido2,$direccion,$Id_pais,$telefono1,$telefono2,$email,$observaciones,$poblacion,$idcli){
		$comunication = new Comunication();
		$params = array($pasaporte,$nombre,$apellido1,$apellido2,$direccion,$Id_pais,$telefono1,$telefono2,$email,$observaciones,$poblacion,$idcli);
		$PARAMS_INSERT = array(Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TINT);
		$result = $comunication->update(self::MODIF_CLIENT,$params,$PARAMS_INSERT);
		return $result;
	}	
	
	public function eliminar_client ($idcli){
		$comunication = new Comunication();
		$params = array($idcli);
		$PARAMS_INSERT = array(Comunication::$TINT);
		$result = $comunication->update(self::ELIM_CLIENT,$params,$PARAMS_INSERT);
		return $result;
	}

}	
?>