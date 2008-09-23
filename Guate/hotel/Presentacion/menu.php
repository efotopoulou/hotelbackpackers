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
			xajax_changeUsuario(id);
		}
</script>

<div id="header-bar">
			<!-- navigation -->
			<ul id="navigation">
				<li><a href="view.php?page=calendario">Calendario</a>
				</li>
				<li><a href="view.php?page=reserva">Reservaciones</a>
				</li>
				<li><a href="view.php?page=checkinres">Check-in</a>
				</li>

				<li><a href="view.php?page=checkout">Check-out</a>

				</li>
				<li><a href="view.php?page=cliente">Clientes</a>
				</li>
				
				<li><a href="view.php?page=factura">Facturas</a></li>
					
				<?php if($allowed){ ?>
				<li><a>Administraci�n</a>
					<ul>
						<li><a href="view.php?page=admin_precios">Precios y Temporadas</a></li>
						<li><a href="view.php?page=admin_users">Usuarios</a></li>
						<li><a href="view.php?page=admin_listados">Listados</a></li>
					</ul>
				</li>
				<li><a href="/Restaurante/Presentacion/view.php?page=tpv">Restaurante</a></li>
				<li><a href="/bar/Presentacion/view.php?page=tpv">Bar</a></li>
				<?php } ?>
				
			</ul>
			
		<div id="userdiv">
			<div style="float:left">
				<select id="selUsers" style="width:120px" onChange="changeId()">
				<?php echo genera_usuarios($sesion, $usr); ?>
				</select>
			</div>
			
			<div style="float:right">
				<a href="view.php?page=login" style="color:#FFFFFF">Salir</a>
			</div>	
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
			$html.='<option id="'.$usr->get_id().'" '.$sel.' >'.$usr->get_nombre().'</option>';
			
		}while($usr->movenext());

		return $html;	
}
?>