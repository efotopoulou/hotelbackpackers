<?php

require ('../Datos/Dbackup.php');

class Backup{
		function setbackup($text){
			$dtp = new Dbackup();
			$rs = $dtp->setbackup($text);
		}
}
?>