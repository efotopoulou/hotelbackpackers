<?php
require ($_SERVER['DOCUMENT_ROOT'] .'/recepcion/Datos/Dalojamiento.php');

class alojamientoRes{

 function insert_checkinmov($idcheck,$idencargado){
   $chic = new Dalojamiento();
   $rs = $chic->insert_checkinmov($idcheck,$idencargado);	
 }

 function insert_checkoutmov($idcheck,$valor, $idencargado){
   $chic = new Dalojamiento();
   $rs = $chic->insert_checkoutmov($idcheck,$valor,$idencargado);	
 }
  
}
?>
