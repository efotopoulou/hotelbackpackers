<?php
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_usuario.php');
$usr=new usuario();
if ($sesion)
	$allowed=$sesion->is_allowed('admin_menu');
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
				<li><a href="/hotel/view.php?page=calendario">Calendario</a>
				</li>
				<li><a href="/hotel/view.php?page=reserva">Reservaciones</a>
				</li>
				<li><a href="/hotel/view.php?page=checkinres">Check-in</a>
				</li>

				<li><a href="/hotel/view.php?page=checkout">Check-out</a>

				</li>
				<li><a href="/hotel/view.php?page=cliente">Clientes</a>
				</li>
				
				<li><a href="/hotel/view.php?page=factura">Facturas</a></li>
					
				<?php if($allowed){ ?>
				<li><a>Admin</a>
					<ul>
						<li><a href="/hotel/view.php?page=admin_precios">Precios y Temporadas</a></li>
						<li><a href="/hotel/view.php?page=admin_users">Usuarios</a></li>
						<li><a href="/hotel/view.php?page=admin_listados">Listados</a></li>
						<li><a href="/common/phpMyBackupPro/">Backup</a></li>
					</ul>
				</li>
				<li><a href="/recepcion/view.php?page=caja">Caja</a></li>
				<?php } ?>
				
			</ul>
			
		<div id="userdiv" style="width:300px">
			<div style="float:left">
				<select id="selUsers" style="width:120px" onChange="changeId()">
				<?php echo genera_usuarios($sesion, $usr); ?>
				</select>
			</div>
			
			<div style="float:right">
				<a href="/hotel/view.php?page=login" style="color:#FFFFFF">Salir</a>
			</div>	
			<div>
				<select id="turno" style="float:left;width:120px;margin-left:10px">
				<option id="Manana">Manana</option>
				<option id="Tarde">Tarde</option>
				<option id="Noche">Noche</option>
				</select>
			</div>
			<div style="clear:both"></div>
		</div>
			
</div>

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