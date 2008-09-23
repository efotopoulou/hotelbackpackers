<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/xajax/xajax.inc.php');

$xajax = new xajax('/hotel/Presentacion/admin_precios.xajax.func.php');

$xajax->registerFunction('changeTempos');
$xajax->registerFunction('changeAlojs');
$xajax->registerFunction('changeTipoAlojs');
$xajax->registerFunction('insertarPrecios');
$xajax->registerFunction('loadAlojPrecios');
$xajax->registerFunction('loadTemporadas');
$xajax->registerFunction('loadEditAlojs');
$xajax->registerFunction('loadEditTipoAlojs');
$xajax->registerFunction('loadTipoAlojPrecios');

?>