<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dusers.php');
 
class class_users{
		function getUsers($sidx, $sord, $start, $limit){
			$datos = new Dusers();
			$rs = $datos->getUsers($sidx, $sord, $start, $limit);
			$i=0;
			if ($rs->getRecordCount()>0){
			while($rs->next()){
				$row=$rs->getRow();
  				$result[$i]['id']=$row["nombre"];
				$result[$i]['cell']=array($row["nombre"],$row["perfil"],"********");
				$i++;
				}
																		
		  }else{
				$result=null;
		  }
		  return $result;
		}
		function getCountUsers(){
			$datos = new Dusers();
			$rs = $datos->getCountUsers();
			$i=0;
			if ($rs->getRecordCount()>0){
				$rs->next();
				$row=$rs->getRow();
				$result=$row["count"];
		  	}else{
				$result=null;
		  	}
		  	return $result;
		}

} 
?>
