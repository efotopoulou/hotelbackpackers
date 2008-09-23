<?php
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_factura.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_checkinres.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_cliente.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_eventos.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_listado.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_precios.php');

switch ($_GET['opc']){
		
		
		case checkinres::$ID:		
			if($_GET['result']==checkinres::$OK)
				$html='
				<div id="text">Checkin Realizado</div>		
				<div style="float:left; margin-left:40px"><input type="button" value="Ir a Calendario" onClick="parent.parent.location.href=\'view.php?page=calendario\'"/></div>
				<div style="float:right; margin-right:40px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
			elseif($_GET['result']==checkinres::$ERR_RES)
				$html='
				<div id="text">Error: No ha seleccionado ninguna reserva</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
			elseif($_GET['result']==checkinres::$ERR_CHECK)
				$html='
				<div id="text">Error: El check-in ya ha sido realizado</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';	
		
		break;
		
		case checkinres::$IDOUT:		
			if($_GET['result']==checkinres::$OK)
				$html='
				<div id="text">Checkout Realizado</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
			elseif($_GET['result']==checkinres::$ERR_CHECK)
				$html='
				<div id="text">Error: El check-out ya ha sido realizado</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
			elseif($_GET['result']==checkinres::$ERR_FRA)
				$html='
				<div id="text">Error: Faltan datos de factura</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';	
		break;
		
		case factura::$ID:
			if($_GET['result']==factura::$OK)
				$html='
				<div id="text">Factura Realizada</div>		
				<div style="float:left; margin-left:40px"><input type="button" value="Ir a Calendario" onClick="parent.parent.location.href=\'view.php?page=calendario\'"/></div>
				<div style="float:right; margin-right:40px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.location.href=\'view.php?page=factura\'"/></div>
				';
			elseif($_GET['result']==factura::$ERR_NUMFRA)
				$html='
				<div id="text">Error: No se ha introducido el número de factura</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		break;
								
		case cliente::$ID:	
		if($_GET['result']==cliente::$OK)
			$html='
				<div id="text">Cliente Insertado</div>
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		elseif($_GET['result']==cliente::$ELIM)
			$html='
				<div id="text">El cliente ha sido eliminado</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		elseif($_GET['result']==cliente::$MODIF)
			$html='
				<div id="text">El cliente ha sido modificado</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		elseif($_GET['result']==cliente::$ERR)
			$html='
				<div id="text">Error:</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
	
		break;
		
		case eventos::$ID:
		if($_GET['result']==eventos::$OK)
			$html='
				<div id="text">Reserva realizada. Precio: '.$_GET['precio'].'Q</div>	
				<div style="float:left; margin-left:40px"><input type="button" value="Ir a Calendario" onClick="parent.parent.location.href=\'view.php?page=calendario\'"/></div>
				<div style="float:right; margin-right:40px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				' ;
		elseif($_GET['result']==eventos::$OK_IMP)
			$html='
				<div id="text">Reserva actualizada</div>
				<div style="float:left; margin-left:40px"><input type="button" value="Ir a Calendario" onClick="parent.parent.location.href=\'view.php?page=calendario\'"/></div>
				<div style="float:right; margin-right:40px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		elseif($_GET['result']==eventos::$ERR_CLI)
			$html='
				<div id="text">Error: No se ha indicado un cliente</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		
		elseif($_GET['result']==eventos::$ERR_ALOJ)
			$html='
				<div id="text">Error: No se ha indicado alojamiento</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';				
		elseif($_GET['result']==eventos::$ERR_FECH)
			$html='
				<div id="text">Error: Fechas incorrectas</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';	
		elseif($_GET['result']==eventos::$ERR_DEL_RES)
			$html='
				<div id="text">La reserva no se ha borrado</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		elseif($_GET['result']==eventos::$ERR_CHG_RES_ALOJ_NODISP)
			$html='
				<div id="text">El alojamiento de destino no está disponible</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';	
		elseif($_GET['result']==eventos::$ERR_CHG_RES_ALOJ)
			$html='
				<div id="text">El alojamiento no se ha cambiado</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		break;
		
		case alojamiento::$ID:
		if($_GET['result']==alojamiento::$OK_INS)
			$html='
				<div id="text">Alojamiento insertado</div>	
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				' ;
		elseif($_GET['result']==alojamiento::$ERR_INS_ALOJ)
			$html='
				<div id="text">El alojamiento no se ha insertado</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		elseif($_GET['result']==alojamiento::$ERR_ALOJ_TIPO)
			$html='
				<div id="text">No se ha seleccionado un tipo de alojamiento</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		elseif($_GET['result']==alojamiento::$OK_ELIM)
			$html='
				<div id="text">El alojamiento se ha eliminado</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		elseif($_GET['result']==alojamiento::$OK_MODIF)
			$html='
				<div id="text">El alojamiento se ha modificado</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';		
		break;
		
		case precios::$ID:		//8
		if($_GET['result']==precios::$OK)
			$html='
				<div id="text">Precios actualizados</div>	
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				' ;
		elseif($_GET['result']==precios::$ERR_NO_TEMP)
			$html='
				<div id="text">No se ha seleccionado temporada</div>		
				<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
				';
		
		break;
		
		case listado::$ID:
		if($_GET['result']==listado::$ERR_HORA)
			$html='
			<div id="text">Error: No se ha introducido la fecha o la hora</div>		
			<div style="float:right; margin-right:100px"><input type="button" style="width:100px" value="Cerrar" onClick="parent.parent.GB_hide()"/></div>
			';
		break;
		
	}


?>

<html>
<head>
  	<link href="css/estilo.css" rel="stylesheet" type="text/css" />


</head>

<body>
<div style="width:100%">
<?php echo $html ?>
</div>
</body>
</html>