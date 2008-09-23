<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/xajax/xajax.inc.php');

$xajax = new xajax('xajax.func.php');
$xajax->registerFunction('loadBox');
$xajax->registerFunction('delRes');
$xajax->registerFunction('loadCli');
$xajax->registerFunction('loadLit');
$xajax->registerFunction('loadFreeRooms');
$xajax->registerFunction('refreshRow');
$xajax->registerFunction('changeEvData');
$xajax->registerFunction('load_chepr');
$xajax->registerFunction('load_ocupantes');
$xajax->registerFunction('load_preu');
$xajax->registerFunction('insertarCli');
$xajax->registerFunction('load_autopaises');
$xajax->registerFunction('changeUsuario');
$xajax->registerFunction('loadLineasFra');
$xajax->registerFunction('makecheckout');
$xajax->registerFunction('changeLineas');
$xajax->registerFunction('load_checkoutpre');
$xajax->registerFunction('cerrarfactura');
$xajax->registerFunction('changeCli');
$xajax->registerFunction('loadLineasCaja');
//$xajax->autoCompressJavascript();
?>