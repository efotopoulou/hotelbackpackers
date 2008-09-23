<?php 
	$db_server					= "localhost";	//Database-Server-Name
	$db_username				= "root";  	//MySQL-Username
	$db_passwort				= ""; 	//MySQL-Password
	$db_database				= "guate_bd"; 	//Database-Name besuchersystem
	database_connect($db_server,$db_username,$db_passwort,$db_database);
	
function database_connect($db_server,$db_username,$db_passwort,$db_database)
{
	$db_connection 				= mysql_connect($db_server,$db_username,$db_passwort) or die("Error: No se puede conectar con '$db_server'."); // Database-Connect
	mysql_select_db($db_database,$db_connection);
}
?>