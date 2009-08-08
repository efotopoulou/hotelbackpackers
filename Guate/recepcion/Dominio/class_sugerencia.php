<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Datos/Dsugerencia.php');
 
class class_sugerencia{
		function setTexto($texto){
			$datos = new Dsugerencia();
			$datos->setTexto($texto);
		}
} 
?>
