<?php
// This code was created by phpMyBackupPro v.2.1 
// http://www.phpMyBackupPro.net

$_POST['db']=array("guate_bd", "recepcion_bd", "restbar_bd" );
$_POST['tables']="on";
$_POST['data']="on";
$_POST['drop']="on";
$_POST['zip']="gzip";
$_POST['mysql_host']="-1";
$period=(3600*24)*0;
$security_key="";
// This is the relative path to the phpMyBackupPro v.2.1 directory
@chdir("../../common/phpMyBackupPro/");
echo "hola2";
@include("backup.php");
echo "hola2";
?>