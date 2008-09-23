<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Datos/Dhabitaciones.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_eventos.php');

class alojamiento{
	private $habit;
	private $taloj;
	
	public static $TIPO_COMUN=1;	
	public static $TIPO_CAMA=5;
	
	public static $ID=6;
	public static $OK_INS=1;
	public static $OK_ELIM=2;
	public static $OK_MODIF=3;
	public static $ERR=-1;
	public static $ERR_INS_ALOJ=-2;
	public static $ERR_ALOJ_TIPO=-3;
	
	function get_all_tipos(){
		$datos = new Dhabitaciones();
		
		$rs = $datos->get_tipos();	
		
		$this->taloj=null;		
		while($rs->next()) {
			$this->taloj[$rs->getInt('Id_tipo')] = array("descripcion"=>$rs->getString('descripcion'), "color"=>$rs->getString('color'));	
		}
		return $rs->getRecordCount();
	}	
	
	function get_all_aloj($id_parent=0){
		$datos = new Dhabitaciones();
		
		if($id_parent)
			$rs = $datos->get_literas($id_parent);
		else
			$rs = $datos->get_aloj_all();	
		
		$this->habit=null;		
		while($rs->next()) {
			$this->habit[$rs->getInt('Id_aloj')] = array("nombre"=>$rs->getString('nombre'), "id_tipo"=>$rs->getInt('Id_tipo'), "tipo"=>$rs->getString('descripcion'), "color"=>$rs->getString('color'), "id_parent"=>$rs->getInt('Id_parent'), "num_matrim"=>$rs->getInt('num_matrim'), "num_indiv"=>$rs->getInt('num_indiv'), "orden"=>$rs->getInt('orden') );	
		}
		return $rs->getRecordCount();
	}
	
	function get_aloj($id_aloj){
		$datos = new Dhabitaciones();
	
		$rs = $datos->get_aloj($id_aloj);
	
		$this->habit=null;
		if ($rs->getRecordCount()>0){
			$rs->next();
			$this->habit[$rs->getInt('Id_aloj')] = array("nombre"=>$rs->getString('nombre'), "id_tipo"=>$rs->getInt('Id_tipo'), "tipo"=>$rs->getString('descripcion'), "color"=>$rs->getString('color'), "id_parent"=>$rs->getInt('Id_parent'), "num_matrim"=>$rs->getInt('num_matrim'), "num_indiv"=>$rs->getInt('num_indiv'), "orden"=>$rs->getInt('orden') );
		}
		return $rs->getRecordCount();
	}
	
	function get_aloj_by_nombre($nombre){
		$datos = new Dhabitaciones();
	
		$rs = $datos->get_aloj_by_name($nombre);
	
		$this->habit=null;
		if ($rs->getRecordCount()>0){
			$rs->next();
			$this->habit[$rs->getInt('Id_aloj')] = array("nombre"=>$rs->getString('nombre'), "id_tipo"=>$rs->getInt('Id_tipo'), "tipo"=>$rs->getString('descripcion'), "color"=>$rs->getString('color'), "id_parent"=>$rs->getInt('Id_parent'), "num_matrim"=>$rs->getInt('num_matrim'), "num_indiv"=>$rs->getInt('num_indiv'), "orden"=>$rs->getInt('orden') );
		}
		return $rs->getRecordCount();
	}
	
	function get_free_aloj($id_parent=0, $fec_ini, $fec_fin){
		$datos = new Dhabitaciones();
		
		if($id_parent)
			$rs = $datos->get_literas($id_parent);
		else
			$rs = $datos->get_aloj_all();	
				
		$this->habit=null;
		$count=0;		
		while($rs->next()) {
				$this->habit[$rs->getInt('Id_aloj')] = array("nombre"=>$rs->getString('nombre'), "id_tipo"=>$rs->getInt('Id_tipo'), "tipo"=>$rs->getString('descripcion'), "color"=>$rs->getString('color'), "id_parent"=>$rs->getInt('Id_parent'), "num_matrim"=>$rs->getInt('num_matrim'), "num_indiv"=>$rs->getInt('num_indiv'));	
				$count++;
		}

		$ev = new eventos();
		$ocup_res=$ev->get_ocup_id_aloj($fec_ini, $fec_fin);		
		if(sizeof($ocup_res)>0)
			foreach($ocup_res as $id){
				unset($this->habit[$id]);
			}		
		return $count-sizeof($ocup_res);
		
	}
	
	function insertar_aloj($data){
		$dtemp = new Dhabitaciones();
			
		if($data['aloj_tipo']==0)  
			$result=alojamiento::$ERR_ALOJ_TIPO;
		else{
			if (strlen($data['aloj_idparent'])>0)
				$data['aloj_idparent']=1;
			else
				$data['aloj_idparent']=0;
		
			$id = $dtemp->insert_aloj($data['aloj_tipo'], $data['aloj_nombre'], $data['aloj_matrim'], $data['aloj_indiv'], $data['aloj_idparent'], $data['aloj_orden']);	
			if($id>0){
				if ($data['aloj_idparent']>0){
						$data['aloj_idparent']=$id;
						$data['aloj_tipo']=alojamiento::$TIPO_COMUN;
						$rs = $dtemp->update_aloj($id, $data['aloj_tipo'], $data['aloj_nombre'], 0, $data['aloj_indiv'], $data['aloj_idparent'], $data['aloj_orden']);
				}
					
				if($data['aloj_tipo']==alojamiento::$TIPO_COMUN){	//es comun
					$count=0; //$this->get_all_aloj($data['id_aloj']);
					while($count<$data['aloj_indiv']){	//se insertan la diferencia de camas
						$count++;
						$nombre=$data['aloj_nombre'].".".sprintf("%02d",$count);
						$res=$dtemp->insert_aloj(alojamiento::$TIPO_CAMA,$nombre,0,1,$id,0);
					}
				}
				$result=alojamiento::$OK_INS;
			}
			else{
				$result=alojamiento::$ERR_INS_ALOJ;
				}
		}
		return $result;	
	}
	
	function modificar_aloj($data){
		$dtemp = new Dhabitaciones();
		
		if (strlen($data['aloj_idparent'])>0){
			$data['aloj_idparent']=$data['id_aloj'];
			$data['aloj_tipo']=alojamiento::$TIPO_COMUN;
		}
		else
			$data['aloj_idparent']=0;
		
		if($data['aloj_idparent']==$data['id_aloj']){	//es comun
			
			$count=$this->get_all_aloj($data['id_aloj']);
			while($count<$data['aloj_indiv']){	//se insertan la diferencia de camas
				$count++;
				$nombre=$data['aloj_nombre'].".".sprintf("%02d",$count);
				$res=$dtemp->insert_aloj(alojamiento::$TIPO_CAMA,$nombre,0,1,$data['id_aloj'],0);
			}
			$data['aloj_indiv']=$count;
		}	
			
		$rs = $dtemp->update_aloj($data['id_aloj'], $data['aloj_tipo'], $data['aloj_nombre'], $data['aloj_matrim'], $data['aloj_indiv'], $data['aloj_idparent'], $data['aloj_orden']);
		
		if($rs>0){
			$result=alojamiento::$OK_MODIF;
		}
		else{
			$result=alojamiento::$ERR;
		}
	
		return $result;
	}
	
	function eliminar_aloj($id_aloj){
		$dtemp = new Dhabitaciones();
		
		if($this->get_aloj($id_aloj)>0){
			$rs = $dtemp->delete_aloj($id_aloj);
			if ($rs>0 && $this->es_comun()){	//si es una comun y se ha borrado, borramos sus camas
					$rs = $dtemp->delete_literas($id_aloj);
			}
			elseif ($rs>0 && $this->es_cama()){	//si es una cama y se ha borrado, actualizamos el num_indiv de la parent
				if($this->get_aloj($this->get_id_parent())>0) //obtenemos el aloj parent
					 $rs = $dtemp->update_aloj($this->get_id(), $this->get_id_tipo(), $this->get_nombre(), $this->get_num_matrim(), $this->get_num_indiv()-1, $this->get_id_parent(), $this->get_orden());
			}
		}
		if($rs>0){
			$result=alojamiento::$OK_ELIM;
		}
		else{
			$result=alojamiento::$ERR;
		}
	
		return $result;
		
	}
	
	function get_id_parent(){
		$a=current($this->habit);
		return $a["id_parent"];	
	}
		
	function get_id(){
		return key($this->habit);		
	}
	
	function get_nombre(){
		$a=current($this->habit);
		return $a["nombre"];		
	}
	
	function get_id_tipo(){
		$a=current($this->habit);
		return $a["id_tipo"];		
	}
	
	function get_tipo(){
		$a=current($this->habit);
		return htmlentities($a["tipo"]);		
	}

	function get_color(){
		$a=current($this->habit);
		return htmlentities($a["color"]);		
	}
	
	function get_num_matrim(){
		$a=current($this->habit);
		return $a["num_matrim"];		
	}
		
	function get_num_indiv(){
		$a=current($this->habit);
		return $a["num_indiv"];		
	}

	function get_orden(){
		$a=current($this->habit);
		return $a["orden"];
	}

	function es_comun(){
		$a=current($this->habit);
		return ($a["id_parent"]==key($this->habit));		
	}
	
	function es_cama(){
		$a=current($this->habit);
		return ($a["id_parent"]!=key($this->habit) && $a["id_parent"]!=0);		
	}
	
	function get_count(){
		return count($this->habit);
	}
	
	function movenext(){
		return next($this->habit);		
	}	
	
	function movefirst(){
		reset($this->habit);
	}	
	
	function current(){
		return current($this->habit);
	}
	
	
	
	//tipo de alojamientos
	
	function insertar_tipo($data){
		$dtemp = new Dhabitaciones();
		$rs = $dtemp->insert_tipo($data['taloj_desc'], $data['taloj_color']);
		return $rs;
	}
	
	function modificar_tipo($data){
		$dtemp = new Dhabitaciones();
		$rs = $dtemp->update_tipo($data['id_taloj'], $data['taloj_desc'], $data['taloj_color']);
		return $rs;
	}
	
	function eliminar_tipo($id_tipo){
		$dtemp = new Dhabitaciones();
		$rs = $dtemp->delete_tipo($id_tipo);
		return $rs;
	}
	
	function t_get_count(){
		return count($this->taloj);
	}
	
	function t_movenext(){
		return next($this->taloj);		
	}
	
	function t_get_id(){
		return key($this->taloj);		
	}
	
	function t_es_tipo_cama(){
		return (key($this->taloj) == alojamiento::$TIPO_CAMA );		
	}
	
	function t_es_tipo_comun(){
		return (key($this->taloj) == alojamiento::$TIPO_COMUN );			
	}
	
	function t_get_descripcion(){
		$a=current($this->taloj);
		return $a["descripcion"];		
	}
	
	function t_get_color(){
		$a=current($this->taloj);
		return $a["color"];		
	}
}

?>
