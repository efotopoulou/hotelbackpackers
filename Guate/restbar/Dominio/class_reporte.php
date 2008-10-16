<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Datos/Dreporte.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/reportmov.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/reportdetail.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/reporttiquets.php');


class reporte{
		
function get_categories(){
	$reporte = new Dreporte();
	$rs = $reporte->get_categories();
	if ($rs->getRecordCount()>0){
	 while($rs->next()){
	$result=$rs->getRow();
		$ors[$result["nombre"]]["entrada"] = 0;
		$ors[$result["nombre"]]["salida"] = 0;
		}														
	}else{
	$result=null;
	}
	return $ors;				
}
function get_resumen($idcaja){
  $reporte = new Dreporte();
  $rs = $reporte->get_resumen($idcaja);
		if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new ReportMov($result["tipo"],$result["categoria"],$result["suma"]);
		$n++;					
		}
    }else{
		$result=null;
	    }
	return $ors;

}
function get_fechaHoraApertura($idcaja){
			$ha = new Dreporte();
			$rs = $ha->get_fechaHoraApertura($idcaja);
			
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["fechaHoraApertura"];
				}
																		
		}else{
				$result=null;
		}
		return $a;
}

function get_fechaHoraCierre($idcaja){
			$hc = new Dreporte();
			$rs = $hc->get_fechaHoraCierre($idcaja);
			
		if ($rs->getRecordCount()>0){
			while($rs->next()){
				$result=$rs->getRow();
				$a=$result["fechaHoraCierre"];
				}
																		
		}else{
				$result=null;
		}
		return $a;
}

function get_reporte($id_caja){
  $reporte = new Dreporte();
  $rs = $reporte->get_reporte($id_caja);
		if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new ReportDetail($result["id_categoria"],$result["date"],$result["time"],$result["descripcion"],$result["entrada"],$result["salida"],$result["categoria"]);
		$n++;					
		}
    }else{
		$result=null;
	    }
	return $ors;

}
function get_tiquets($id_caja){
  $reporte = new Dreporte();
  $rs = $reporte->get_tiquets($id_caja);
		if($rs->getRecordCount()>0){ 
		$n=0;
		while($rs->next()){
		$result=$rs->getRow();
		$ors[$n] = new ReportTiquets($result["fecha"],$result["time"],$result["idComanda"],$result["total"]);
		$n++;					
		}
    }else{
		$result=null;
	    }
	return $ors;

}
}
?>

