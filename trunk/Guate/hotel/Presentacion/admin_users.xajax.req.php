<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/xajax/xajax.inc.php');

$xajax = new xajax('/hotel/Presentacion/admin_users.xajax.func.php');

$xajax->registerFunction('loadUsers');
$xajax->registerFunction('changeUsers');
$xajax->registerFunction('loadPerfiles');
$xajax->registerFunction('changePerfiles');
$xajax->registerFunction('loadPerfilesRest');

?>