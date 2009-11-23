<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dusuario.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/Usuario.php');


class user {
	
	private $usr;
	
	//TODO: Hacer esta función utilizando la clase Usuario.php	
	function get_usuarios($id_perfil=0){
 		$datos=new Dusuario();
 		
 		if($id_perfil)
 			$rs=$datos->get_usuario_by_perfil($id_perfil);
 		else
 			$rs=$datos->get_usuario_all();
 		$this->usr=null;
 		while($rs->next()) {
			$this->usr[$rs->getInt('Id_usuario')] = array("nombre"=>$rs->getString('nombre'), "id_perfil"=>$rs->getInt('Id_perfil'), "email"=>$rs->getString('email'));	
		}
		return $rs->getRecordCount();
 	}
	//Función que se utiliza en el login para saber si el id pasado existe en la BBDD.
	//Si existe, pone en la variable privada $usr los valores del usuario seleccionado. 
	function existe_usuario($id_usr){
 		$datos=new Dusuario();
 		
		$rs=$datos->existe_usuario($id_usr);	
 		
 		if($rs->getRecordCount()>0){
 			$rs->next();
			$this->usr = new Usuario($rs->getInt('Id_usuario'),$rs->getInt('Id_perfil'),$rs->getString('nombre'), $rs->getString('email'));	
 		}
		return $rs->getRecordCount();
 	}
 	
	function insertar_usuario($data){
		$dtemp = new Dusuario();
		$pass=md5($data['password']);
		$rs = $dtemp->insert_usuario($data['user_idperfil'], $data['user_nombre'],$pass, $data['email']);
		return $rs;
	}
	
	function modificar_usuario($data){
		$dtemp = new Dusuario();
		$pass=md5($data['password']);
		$rs = $dtemp->update_usuario($data['user_id'],$data['user_idperfil'], $data['user_nombre'],$pass,$data['email']);
		return $rs;
	}
	
	function eliminar_usuario($id_usr){
		$dtemp = new Dusuario();
		
		$rs = $dtemp->delete_usuario($id_usr);
		return $rs;
	}
		
	function get_id(){
		return key($this->usr);		
	}
	
	function get_nombre(){
		$a=current($this->usr);
		return $a['nombre'];		
	}
	
	//PRE: Se tiene que haber llamado antes a la función existe_usuario,
	//que carga la información del usuario en la variable privada.
	function get_id_perfil(){
		//$a=current($this->usr);
		//return $a['id_perfil'];
		$a=$this->usr->Id_perfil;
		return $a;			
	}
	//Esta funcion se llama solo cuando se obtienen todos los 
	function get_idPerfil(){
		$a=current($this->usr);
		return $a['id_perfil'];		
	}

	function get_mail(){
		$a=current($this->usr);
		return $a['email'];
	}
	
	function get_count(){
		return count($this->usr);
	}
	
	function movenext(){
		return next($this->usr);		
	}	
	
	function movefirst(){
		reset($this->usr);
	}	
	
	function current(){
		return current($this->usr);
	}
	
}