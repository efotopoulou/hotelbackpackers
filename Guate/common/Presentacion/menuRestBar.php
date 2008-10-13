<?php
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_usuario.php');
$usr=new usuario();
if ($sesion){
	$allowedRest=$sesion->is_allowed_rest();}
else
	exit();
?>

<script>
		function changeId(){
			var indice = document.getElementById('selUsers').selectedIndex;
			id=document.getElementById('selUsers').options[indice].id;
			if(typeof(xajax_changeUsuario) != 'undefined') xajax_changeUsuario(id);
			if(typeof(jQuery) != 'undefined') {
			  $.getJSONGuate("Presentacion/jsonchangeUser.php",{ id: id}, function(json){
    			json = verificaJSON(json);
			  });	
			}
		}
</script>

<div id="header-bar">
	<!-- navigation -->
	<ul id="navigation">
<?php if($allowedRest["caja"]){ ?><li><a href="/restbar/view.php?page=caja">Caja</a></li><?php } ?>
<?php if($allowedRest["bebida"]){ ?><li><a href="/restbar/view.php?page=bebida">Bebida</a></li><?php } ?>
<?php if($allowedRest["comida"]){ ?><li><a href="/restbar/view.php?page=comida">Comida</a></li><?php } ?>
<?php if($allowedRest["historicocaja"]){ ?><li><a href="/restbar/view.php?page=historicocaja">Historico Caja</a></li><?php } ?>
<?php if($allowedRest["controlstock"]){ ?><li><a href="/recepcion/view.php?page=controldestock">Control de Stock</a></li><?php } ?>
				
			</ul>
			
		<div id="userdiv" style="width:190px">
			<div style="float:left">
				<select id="selUsers" onChange="changeId()" style="font-size:10px">
				<?php echo genera_usuarios($sesion, $usr); ?>
				</select>
			</div>
			
			<div style="float:right">
				<a href="/hotel/view.php?page=login" style="color:#FFFFFF">Salir</a>
			</div>	
			<div>
				<select id="turno" style="float:left;margin-left:10px;font-size:10px">
				<option id="Manana">Manana</option>
				<option id="Tarde">Tarde</option>
				</select>
			</div>
			<div style="clear:both"></div>
		</div>
</div>
<div style="clear:both"></div>

<?php
function genera_usuarios($ses, $usr){
		$usr->get_usuarios($ses->get_id_perfil());
			
		if($usr->get_count())
		do{
		if($usr->get_id()==$ses->get_id_usuario())
			$sel='selected';
		else
			$sel='';
			//$html.='<option id="'.$usr->get_id().'" '.$sel.' >'.$usr->get_nombre().'</option>';
			$html.='<option value="'.$usr->get_id().'" id="'.$usr->get_id().'" '.$sel.' >'.$usr->get_nombre().'</option>';
			
		}while($usr->movenext());

		return $html;	
}
?>