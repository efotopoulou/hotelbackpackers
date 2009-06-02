<?php

include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_usuario.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_backup.php');

$id=0;
$usr=new usuario();
$onload="view.php?page=calendario";
$message="";
if($_POST!=null){
	
	$id = $_POST['id_usr'];
	$pass = $_POST['pass'];
        $ser = $_POST['servicio'];

	if($usr->get_usuario($id))
		$id_perfil=$usr->get_id_perfil();
		
	if($sesion->validar_perfil($id_perfil, $pass)){		
		$sesion->set_id_usuario($id);
		if($ser=="Rest.") $onload="/restbar/view.php?page=comidaRest";
		if($ser=="Backup") $onload="/common/phpMyBackupPro/";
		if($ser=="Cocina") $onload="/restbar/Presentacion/cocina.php";
		$redir=true;
		
		$log=new log();
		$log->insertar_log($sesion->get_id_usuario(), log::$USR_LOGIN, 0);
	}
	else{
		$message= 'login incorrecto';
	}
}
else{
	$sesion->endSession();	
}

//$backup=new backup();
//$backup->hacer_backup();


//system("mysqldump --host=servidor_sql --user=nombre_de_la_base nombre_de_la_base > nombre_de_la_base.sql");
//echo system($_SERVER['DOCUMENT_ROOT'] ."/bin/mysqldump --host=localhost --user=root guate_bd cliente usuario > nombre_de_la_base.sql", $ret);
//echo $ret;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="MSSmartTagsPreventParsing" content="true">
	
	<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<head>
		 
	<script language="JavaScript">
		
		var idLast=<?php echo $id ?>;
		function selectUser(id){
			if(idLast>0)
				document.getElementById(idLast).className="";
			document.getElementById(id).className="selected";
			idLast=id;
			document.FormLogin.id_usr.value = id;	
			document.FormLogin.pass.focus();
			document.getElementById('msg').innerHTML="";
		}
		function formsubmit(where){
			document.getElementById("servicio").value = where;
			document.FormLogin.submit();
		}
	</script>

</head>

<body <?php if($redir) echo 'onLoad="document.location.href=\''.$onload.'\'"'; ?>>

<div id="base" style="background-color:#ecf8cb">


	<div class="login">  
<div style="float:left">
 <img src="img/logoprograma.jpg"/>
</div>
	<div style="float:right;  padding-right:250px">
		<form action="view.php?page=login" method="POST" name="FormLogin">
		<table class="t_general" style="background:#ecf8cb">
			<tr><td  valign="top" style="padding-right:10px;text-align:right">Usuarios:</td>
			<td><?php echo genera_usuarios($usr,$id) ?></td></tr>
			
			<tr><td style="padding:10px;text-align:right">Contrase&#0241;a:</td>
			<td><input id="pass" name="pass" style="width:103px" type="password"/></td></tr>
			
			<tr><td colspan=2>
			 <input type="button" value="Hotel" onClick="formsubmit(this.value)"/>
			 <input type="button" style="margin-left:10px" value="Rest." onClick="formsubmit(this.value)"/>
			 <input type="button" style="margin-left:10px" value="Backup" onClick="formsubmit(this.value)"/>
 			 <input type="button" style="margin-left:10px" value="Cocina" onClick="formsubmit(this.value)"/></td>
			 
			</tr>
		</table>
		<input id="id_usr" name="id_usr" type="hidden"  value="<?php echo $id ?>"/>
		<input id="servicio" name="servicio" type="hidden" />
		</form>
		</div>
		<div id="msg"><?php echo $message ?></div>
	</div>
	
</div>

</body>
</html>

<?php
	function genera_usuarios($usr, $id){
		$usr->get_usuarios();		
		$html= '<table class="t_general fondo_tabla">';

		if($usr->get_count())
			do{
				if($id==$usr->get_id())
					$sel=' selected';
				else
					$sel='';
				$html.= '<tr class="t_row'.$sel.'" id="'.$usr->get_id().'" style="cursor:pointer" onclick="selectUser(this.id)">' .
					'<td class="t_col" style="text-align: center; padding:5px; width:90px">'.$usr->get_nombre().'</td></tr>';
			}while($usr->movenext());
		
		$html.='</table>';

		return $html;	
	}
?>