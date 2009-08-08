<?php

include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_usuario.php');

$id=0;
$usr=new user();
//$onload="view.php?page=calendario";
$message="";
if($_POST!=null){ //Cuando se introduce el usuario y el password, login.php se envía el form a si misma via POST. 
	$id = $_POST['id_usr'];
	$pass = $_POST['pass'];
    //$ser = $_POST['servicio'];

	if($usr->existe_usuario($id))
		$id_perfil=$usr->get_id_perfil();
		
	if($sesion->validar_usuario($id, $id_perfil, $pass)){		
		$sesion->set_id_usuario($id);
		$onload=$sesion->getOnLoad($id_perfil);
		//if($ser=="Rest.") $onload="/restbar/view.php?page=comidaRest";
		//if($ser=="Backup") $onload="/common/phpMyBackupPro/";
		//if($ser=="Cocina") $onload="/restbar/Presentacion/cocina.php";
		$redir=true;
		$log=new log();
		$log->insertar_log($sesion->get_id_usuario(), log::$USR_LOGIN, 0);
	}
	else{
		$redir=false;
		$showMessage=true;
	}
}
else{
	$sesion->endSession();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
	<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<head>
	<script src="/common/js/jquery-1.2.3.pack.js"></script>
	<script src="/common/js/jquery.blockUI.js"></script>
	<script language="JavaScript">
	//TODO: Poner este codigo de Javascript con Jquery	
		var idLast=<?php echo $id ?>;
		function selectUser(id){
			if(idLast>0)
				document.getElementById(idLast).className="";
			document.getElementById(id).className="selected";
			idLast=id;
			document.FormLogin.id_usr.value = id;	
			document.FormLogin.pass.focus();
		}
		function formsubmit(where){
			document.FormLogin.submit();
		}
	</script>
	<script type="text/javascript"> 
    $(document).ready(function() { 
        $('#continue').click(function() { 
            $.unblockUI(); 
            return false; 
        }); 
     }); 
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
			<tr><td  valign="top" style="padding-right:10px;text-align:right"></td>
			<td><?php echo genera_usuarios($usr,$id) ?></td></tr>
			
			<tr><td style="padding:10px;text-align:right">Contrase&#0241;a:</td>
			<td><input id="pass" name="pass" style="width:103px" type="password"/></td></tr>
			
			<tr><td colspan=2><center>
			   <input type="button" value="Entrar" onClick="formsubmit(this.value)"/></center>
			 </td>
			 
			</tr>
		</table>
		<input id="id_usr" name="id_usr" type="hidden"  value="<?php echo $id ?>"/>
		</form>
		</div>
	</div>
	
</div>
<div id="question" style="display:none; cursor: default"> 
        <h1>Error!</h1><br />El nombre de usuario o la contrase&ntilde;a son incorrectos.<br /> Vuelva a intentarlo.<br /><br /> 
        <input type="button" id="continue" value="Continuar" /> 
</div> 

<?php if($showMessage){ //Si se han equivocado en el login, se muestra un mensaje de error?>
<script>$.blockUI({ message: $('#question'), css: { width: '275px' } });</script>
<?php }?>
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