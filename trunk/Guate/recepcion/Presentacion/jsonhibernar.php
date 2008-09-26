<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_comanda.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_backup.php');

 $mensaje = new MensajeJSON();
 $comanda = new Comanda();
 $backup = new Backup();
  
 $restore = $_POST['restore'];
 $json = $_POST['json'];
 $json = str_replace("\\", "",$json);
 if (!$restore) $backup->setbackup($json);
 $json = json_decode($json, true);
 $result = array();

 if ($restore){
	$comandasRestore = $comanda->comandasRestore();
	$mensaje->setDatos($comandasRestore);
	$comanda->borrarComanda("backup");
 }else {
  for ($i = 0;$i< sizeof($json);$i++){
 	$comandaJSON = $json[$i];
 	if ($comanda->existeIdComanda($comandaJSON["comandaID"])){
 		//EXISTE IDCOMANDA
 	}else {
 		//NO EXISTE IDCOMANDA
 		//COMPROVAR LOS PARAMETROS ENVIADOS!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 		$comanda->setComanda($comandaJSON["comandaID"],"backup",$comandaJSON["efectivo"],$comandaJSON["mesa"], $comandaJSON["currentClientType"], $comandaJSON["total"], $comandaJSON["id_cliente"],$comandaJSON["free"]);
		$lineas = $comandaJSON["liniasComanda"]; 		
 		for ($j=0;$j<=$comandaJSON["numRow"];$j++){
 			$cantidad = (int)$lineas[$j]["cantidad"];
 			if($cantidad==0) $cantidad=1;
 			$comanda->setLineaComandaNoCocina($comandaJSON["comandaID"],$lineas[$j]["platoId"],$cantidad, $lineas[$j]["precioN"]);
 		}
 		
 	}
  }
 }
echo($mensaje->encode());

?>