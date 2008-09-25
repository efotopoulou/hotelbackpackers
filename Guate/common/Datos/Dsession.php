<?php

require_once ('Comunication.php');

class Dsession{
	const GET_PERMISO = 'SELECT permiso FROM pagina p, perfilpagina pp where p.id_pagina = pp.id_pagina AND p.nombre=? AND pp.id_perfil=?';
	const GET_PERFIL_ALL = 'SELECT * FROM perfil ORDER BY nombre';
	const GET_PERFIL_REST_ALL = 'SELECT p.id_perfil, gp.nombre, p.id_pagina, rp.nombre as nombrepagina, p.permiso FROM restaurante_bd.perfilpagina p, guate_bd.perfil gp, restaurante_bd.pagina rp where p.id_perfil = gp.id_perfil and rp.id_pagina = p.id_pagina';
    const GET_PAGINA_REST_ALL = 'SELECT nombre FROM  restaurante_bd.pagina';	

	const GET_PERFIL_REST = 'SELECT p.id_perfil, rp.nombre as nombrepagina, p.permiso FROM restaurante_bd.perfilpagina p, restaurante_bd.pagina rp where p.id_perfil = ? and rp.id_pagina = p.id_pagina';

	const GET_PERFIL_BY_ID = 'SELECT * FROM perfil WHERE Id_perfil=?';
	
	const INSERT_PERFIL = 'INSERT INTO perfil VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
	const UPDATE_PERFIL = 'UPDATE perfil SET nombre=?, password=?, calendario=?, reserva=?, checkinres=?, checkout=?, cliente=?, caja=?, admin_menu=?, admin_precios=?, admin_users=?, admin_listados=? WHERE Id_perfil=?';
	const DELETE_PERFIL = 'DELETE FROM perfil WHERE Id_perfil=?';
		
		
	public function get_perfil_all(){
		$comunication = new Comunication();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_PERFIL_ALL,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	

	public function get_perfil_rest_all(){
		$comunication = new Comunication();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_PERFIL_REST_ALL,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}
	public function get_paginas_rest_all(){
		$comunication = new Comunication();
		$PARAMS = array();
		$PARAMS_TYPES = array ();
		$result = $comunication->query(self::GET_PAGINA_REST_ALL,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	
		

	public function get_perfil_rest($id){
		$comunication = new Comunication();
		$PARAMS = array($id);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_PERFIL_REST,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}	

	public function get_perfil($id){
		$comunication = new Comunication();
		$PARAMS = array($id);
		$PARAMS_TYPES = array (Comunication::$TINT);
		$result = $comunication->query(self::GET_PERFIL_BY_ID,$PARAMS,$PARAMS_TYPES);
		
		return $result;
	}

	public function tiene_permiso_rest($id, $page){
		$comunication = new Comunication();
		$PARAMS = array($page,$id);
		$PARAMS_TYPES = array (Comunication::$TSTRING, Comunication::$TINT);
		$result = $comunication->query(self::GET_PERMISO,$PARAMS,$PARAMS_TYPES);
		$result->next();
		$rs = $result->getRow();
		return $rs["permiso"];

	}

	public function insert_perfil($nombre, $password, $calendario, $reserva, $checkinres, $checkout, $cliente, $caja, $admin_menu, $admin_precios, $admin_users, $admin_listados){
		$params = array(0, $nombre, $password, $calendario, $reserva, $checkinres, $checkout, $cliente, $caja, $admin_menu, $admin_precios, $admin_users, $admin_listados);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT,Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN);
		$result = $comunication->update(self::INSERT_PERFIL,$params,$PARAMS_TYPES);
		return $result;
	}
	
	public function update_perfil($id_perfil, $nombre, $password, $calendario, $reserva, $checkinres, $checkout, $cliente, $caja, $admin_menu, $admin_precios, $admin_users, $admin_listados){
		$params = array($nombre, $password, $calendario, $reserva, $checkinres, $checkout, $cliente, $caja, $admin_menu, $admin_precios, $admin_users, $admin_listados, $id_perfil);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TSTRING,Comunication::$TSTRING,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TBOOLEAN,Comunication::$TINT);
		$result = $comunication->update(self::UPDATE_PERFIL,$params,$PARAMS_TYPES);
		return $result;
	}
	
	public function delete_perfil($id_perfil){
		$params = array($id_perfil);
		
		$comunication = new Comunication();
		$PARAMS_TYPES = array(Comunication::$TINT);
		$result = $comunication->update(self::DELETE_PERFIL,$params,$PARAMS_TYPES);
		return $result;
	}
}
?>