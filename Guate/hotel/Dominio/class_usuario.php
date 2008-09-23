<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dusuario.php');


class usuario {
	
	private $usr;
		
	function get_usuarios($id_perfil=0){
 		$datos=new Dusuario();
 		
 		if($id_perfil)
 			$rs=$datos->get_usuario_by_perfil($id_perfil);
 		else
 			$rs=$datos->get_usuario_all();
 		$this->usr=null;
 		while($rs->next()) {
			$this->usr[$rs->getInt('Id_usuario')] = array("nombre"=>$rs->getString('nombre'), "id_perfil"=>$rs->getInt('Id_perfil'));	
		}
		return $rs->getRecordCount();
 	}
	
	function get_usuario($id_usr){
 		$datos=new Dusuario();
 		
		$rs=$datos->get_usuario($id_usr);	
 		
 		if($rs->getRecordCount()>0){
 			$rs->next();
			$this->usr[$rs->getInt('Id_usuario')] = array("nombre"=>$rs->getString('nombre'), "id_perfil"=>$rs->getInt('Id_perfil'));	
 		}
		return $rs->getRecordCount();
 	}
 	
	function insertar_usuario($data){
		$dtemp = new Dusuario();
		
		$rs = $dtemp->insert_usuario($data['user_idperfil'], $data['user_nombre']);
		return $rs;
	}
	
	function modificar_usuario($data){
		$dtemp = new Dusuario();
		
		$rs = $dtemp->update_usuario($data['user_id'],$data['user_idperfil'], $data['user_nombre']);
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
	
	function get_id_perfil(){
		$a=current($this->usr);
		return $a['id_perfil'];		
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