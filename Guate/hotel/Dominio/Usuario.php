<?php
class Usuario{
    var $Id_usuario;
    var $Id_perfil;
    var $Nombre;
    var $Email;
    	
	function Usuario($Id_usuario,$Id_perfil, $Nombre, $email){
	$this->Id_usuario=$Id_usuario;
	$this->Id_perfil=$Id_perfil;
	$this->Nombre=$Nombre;
	$this->Email=$email;
	}
}
?>