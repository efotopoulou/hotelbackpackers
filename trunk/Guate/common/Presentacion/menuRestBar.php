<?php
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_usuario.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_turno.php');

$turno=new turno();
$turn=$turno->get_turno_caja();

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
<?php if($allowedRest["cajaRest"]){ ?><li><a href="/restbar/view.php?page=cajaRest">Caja</a></li><?php } ?>
<?php if($allowedRest["bebidaRest"]){ ?><li><a href="/restbar/view.php?page=bebidaRest">Bebida</a></li><?php } ?>
<?php if($allowedRest["comidaRest"]){ ?><li><a href="/restbar/view.php?page=comidaRest">Comida</a></li><?php } ?>
<?php if($allowedRest["historicocajaRest"]){ ?><li><a href="/restbar/view.php?page=historicocajaRest">Historico Caja</a></li><?php } ?>
<?php if($allowedRest["controldestockRest"]){ ?><li><a href="/restbar/view.php?page=controldestockRest">Control de Stock</a></li><?php } ?>
<?php if($allowedRest["cocina"]){ ?><li><a href="/restbar/view.php?page=cocina">Cocina</a></li><?php } ?>
				
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
			<div id="turno"><center>Turno de <?php echo($turn); ?></center></div>
			
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