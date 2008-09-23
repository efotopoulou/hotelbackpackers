<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dlistado.php');

class listado{
	
	private $listado;
	public static $ID=100;
		
	public static $OK=1;
	public static $ERR_HORA=-1;
	public static $ERR=-2;
	
	
	function __construct(){
	
		$this->listado['caja']=array(
		"descripcion"=>'',			
		"tabla"=>'caja',
		"select"=>'Id_movimiento,DATE_FORMAT(fecha,"%d-%m-%Y %H:%i") AS "Fecha",importe,modo_pago,descripcion',
		"condicion"=>'',
		"id_tabla"=>'Id_movimiento',
		"col_orden"=>0);
		
		$this->listado['clientes']	=array(
			"descripcion"=>'Lista de clientes',
			"tabla"=>'cliente as c, pais as p',
			"select"=>'Id_cliente, c.nombre as Nombre, c.apellido1 as Apellido1, c.apellido2 as Apellido2, direccion as Dirección, poblacion as Población, nom_pais as País, telefono1 as Tel1,telefono2 as Tel2, email, observaciones as Observaciones',
			"condicion"=>'c.Id_pais=p.Id_pais',
			"id_tabla"=>'Id_cliente',
			"col_orden"=>1);

		$this->listado['alojamientos']	=array(
			"descripcion"=>'',			
			"tabla"=>'alojamiento as a, alojamiento_tipo as at',
			"select"=>'Id_aloj, nombre as Nombre, at.descripcion as Tipo, num_matrim as "Camas Matrim", num_indiv as "Camas Indiv", Id_parent, orden',
			"condicion"=>'a.Id_tipo=at.Id_tipo',
			"id_tabla"=>'Id_aloj',
			"col_orden"=>0);
			
		$this->listado['precios']=array(
			"descripcion"=>'Lista de alojamientos y precios',			
			"tabla"=>'alojamiento as a, precio as p, precio_tipo as pt',
			"select"=>'a.Id_aloj,a.nombre,p.precio, pt.nombre as "Tipo_Precio"',
			"condicion"=>'a.Id_aloj=p.Id_aloj and p.Id_tipo=pt.Id_tipo',
			"id_tabla"=>'Id_aloj',
			"col_orden"=>2);			
			
		$this->listado['log']=array(
			"descripcion"=>'Registro de actividad en el sistema',			
			"tabla"=>'logs as l, log_accion as a, usuario as u',
			"select"=>'Id_log, DATE_FORMAT(fecha,"%d-%m-%Y %H:%i") AS "Fecha" ,nombre,descripcion',
			"condicion"=>'l.Id_accion=a.Id_accion and l.Id_usuario=u.Id_usuario',
			"id_tabla"=>'Id_log',
			"col_orden"=>0);
	
		$this->listado['hab_res']=array(
			"descripcion"=>'Habitaciones reservadas',			
			"tabla"=>'reserva as r, alojamiento as a, cliente as c, alojamiento_tipo as at',
			"select"=>'r.Id_res,DATE_FORMAT(r.fec_ini,"%d-%m-%Y") as "Fecha_Inicio",DATE_FORMAT(r.fec_fin,"%d-%m-%Y") as "Fecha_Fin",concat(a.nombre,". ",at.descripcion) as "Alojamiento",c.nombre as "Nombre", c.apellido1 as Apellido',
			"condicion"=>'r.Id_aloj=a.Id_aloj AND r.Id_cliente=c.Id_cliente AND a.Id_tipo=at.Id_tipo',
			"id_tabla"=>'Id_res',
			"col_orden"=>0);
	
		$this->listado['hab_ocup']=array(
			"descripcion"=>'Habitaciones ocupadas',			
			"tabla"=>'reserva as r, checkin as ch, alojamiento as a, cliente as cl, alojamiento_tipo as at',
			"select"=>'Id_checkin, concat(a.nombre,". ",at.descripcion) as "Alojamiento", DATE_FORMAT(ch.fec_in,"%d-%m-%Y") as "Fecha_Entrada", DATE_FORMAT(DATE_ADD(r.fec_fin, INTERVAL 1 DAY),"%d-%m-%Y") as "Fecha_Salida", cl.nombre as "Nombre", cl.apellido1 as Apellido',
			"condicion"=>'r.Id_res=ch.Id_res AND r.Id_aloj=a.Id_aloj AND a.Id_tipo=at.Id_tipo AND r.Id_cliente=cl.Id_cliente AND ch.fec_out IS NULL',
			"id_tabla"=>'Id_checkin',
			"col_orden"=>0);
			
		$this->listado['factura']=array(
			"descripcion"=>'Facturas',			
			"tabla"=>'factura',
			"select"=>'Id_fra,num_fra, fecha_fra, nombre, nit, total',
			"condicion"=>'',
			"id_tabla"=>'Id_fra',
			"col_orden"=>0);	
	}
	
	function get_id(){
		return key($this->listado);		
	}
	function get_tabla($id=''){
		if(strlen($id)>0)
			$a=$this->listado[$id];
		else	
			$a=current($this->listado);
		return $a["tabla"];		
	}
	function get_descripcion($id=''){
		if(strlen($id)>0)
			$a=$this->listado[$id];
		else	
			$a=current($this->listado);
		return $a["descripcion"];		
	}
	function get_select($id=''){
		if(strlen($id)>0)
			$a=$this->listado[$id];
		else	
			$a=current($this->listado);
		return $a["select"];		
	}
	function get_condicion($id=''){
		if(strlen($id)>0)
			$a=$this->listado[$id];
		else		
			$a=current($this->listado);
		return $a["condicion"];		
	}
	
	function get_col_orden($id=''){
		if(strlen($id)>0)
			$a=$this->listado[$id];
		else		
			$a=current($this->listado);
		return $a["col_orden"];		
	}
	
	function set_condicion($cond, $id){
		$this->listado[$id]["condicion"]=$cond;		
	}
	
	
	function get_id_tabla($id=''){
		if(strlen($id)>0)
			$a=$this->listado[$id];
		else		
			$a=current($this->listado);
		return $a["id_tabla"];		
	}
	
	function movenext(){
		return next($this->listado);		
	}
		function get_count(){
		return count($this->listado);
	}
}