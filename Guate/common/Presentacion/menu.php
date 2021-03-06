<?php
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_usuario.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_turno.php');

$turno=new turno();
$turn=$turno->get_turno_caja();

$usr=new user();
if ($sesion){
	$allowed=$sesion->is_allowed('admin_menu');
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
			  $.getJSONGuate("/recepcion/Presentacion/jsonchangeUser.php",{ id: id}, function(json){
    			json = verificaJSON(json);
			  });	
			}
		}
</script>

<div id="header-bar">
			<!-- navigation -->
			    <ul id="navigation">
				<li><a href="/hotel/view.php?page=calendario">Calendario</a></li>
		<li><a>Opciones Hotel</a>
			<ul>
				
				<li><a href="/hotel/view.php?page=reserva">Reservaciones</a></li>
				<li><a href="/hotel/view.php?page=checkinres">Check-in</a></li>
                <li><a href="/hotel/view.php?page=checkout">Check-out</a></li>
				<li><a href="/hotel/view.php?page=cliente">Clientes</a></li>
				<li><a href="/hotel/view.php?page=factura">Facturas</a></li>
				<?php if($allowed){ ?>
				<li><a style="font-weight:bold">Admin Hotel:</a></li>
				<li><a href="/hotel/view.php?page=admin_precios">Precios y Temporadas</a></li>
				<li><a href="/hotel/view.php?page=admin_users">Usuarios</a></li>
				<li><a href="/hotel/view.php?page=admin_listados">Listados</a></li>
				<li><a href="/common/phpMyBackupPro/">Backup</a></li>
				<?php } ?>

		    </ul>
		</li>		
<?php if($allowedRest["caja"]){ ?><li><a href="/recepcion/view.php?page=caja">Caja</a></li><?php } ?>
				<li><a>Opciones Recepci&oacute;n</a>
			<ul>
<?php if($allowedRest["ventarecepcion"]){ ?><li><a href="/recepcion/view.php?page=ventarecepcion">Venta en Recepci&oacute;n</a></li><?php } ?>
<?php if($allowedRest["cuentausuarios"]){ ?><li><a href="/recepcion/view.php?page=cuentausuarios">Cuenta De Usuarios</a></li><?php } ?>
<?php if($allowedRest["controldestock"]){ ?><li><a href="/recepcion/view.php?page=controldestock">Control de Stock</a></li><?php } ?>
<?php if($allowedRest["historicocaja"]){ ?><li><a href="/recepcion/view.php?page=historicocaja">Hist&oacute;rico Caja</a></li><?php } ?>
<?php if($allowedRest["estadisticas"]){ ?><li><a href="/recepcion/view.php?page=estadisticas">Estad&iacute;sticas</a></li><?php } ?>
<?php if($allowedRest["sugerencias"]){ ?><li><a href="/recepcion/view.php?page=sugerencias">Sugerencias</a></li><?php } ?>
<?php if($allowedRest["gestionbebidas"]){ ?><li><a style="font-weight:bold">Admin Recepci&oacute;n:</a></li><?php } ?>
<?php if($allowedRest["gestionbebidas"]){ ?><li><a href="/recepcion/view.php?page=gestionbebidas">Gesti&oacute;n de Bebidas</a></li><?php } ?>
<?php if($allowedRest["gestionplatillos"]){ ?><li><a href="/recepcion/view.php?page=gestionplatillos">Gesti&oacute;n de Platillos</a></li><?php } ?>
<?php if($allowedRest["buscarporcomanda"]){ ?><li><a href="/recepcion/view.php?page=buscarporcomanda">Buscar por Comanda</a></li><?php } ?>
<?php if($allowedRest["platillosmaspedidos"]){ ?><li><a href="/recepcion/view.php?page=platillosmaspedidos">Platillos mas pedidos</a></li><?php } ?>

		    </ul>
		</li>		
<?php if($allowedRest["cajaRest"]){ ?><li><a href="/restbar/view.php?page=cajaRest">Ir a Restaurante</a></li><?php } ?>
			</ul>
			
		<div id="userdiv" style="width:190px">
			<div style="float:left;display:none">
			    <span style="font-size:15px;margin-right:10px;font-weight:bold"><a href="/common/docs/manualdeinstalacionwindows.pdf" target="blank">?</a></span>
			    <select id="selUsers" onChange="changeId()" style="font-size:10px">
				<?php echo genera_usuarios($sesion, $usr); ?>
				</select>
			</div>
			
			<div style="float:right">
				<a href="/hotel/view.php?page=login" style="color:#FFFFFF">Salir</a>
			</div>	
			<div id="turno"  style="text-align:center"><span class='turnico'><?php echo($turn); ?></span></center></div>
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