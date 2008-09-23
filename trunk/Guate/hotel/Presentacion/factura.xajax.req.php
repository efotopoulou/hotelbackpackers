<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/xajax/xajax.inc.php');

$xajax = new xajax('/hotel/Presentacion/factura.xajax.func.php');

$xajax->registerFunction('loadCheckins');
$xajax->registerFunction('loadLineasFra');
$xajax->registerFunction('addCheckinToFra');
$xajax->registerFunction('elimLineaFromFra');
$xajax->registerFunction('loadFrasOpened');
$xajax->registerFunction('closeFactura');
?>