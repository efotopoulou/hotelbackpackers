<?php

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']))
     exit;
     
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/xajax.req.php');
$xajax->printJavascript('xajax/'); ?>
	
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<meta name="MSSmartTagsPreventParsing" content="true">
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

	<link href="css/estilo.css" rel="stylesheet" type="text/css" />
	
	<script src="scripts/nav.js"></script>	

	<link href="estilo.css" rel="stylesheet" type="text/css" />
	<link href="scripts/calendar-green.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/calendar.js"></script>
	<script type="text/javascript" src="scripts/calendar-sp.js"></script>
	<script type="text/javascript" src="scripts/calendar-setup.js"></script>

	<script type="text/javascript">
	    var GB_ROOT_DIR = "/hotel/scripts/greybox/";
	</script>
	
	<script type="text/javascript" src="scripts/greybox/AJS.js"></script>
	<script type="text/javascript" src="scripts/greybox/gb_scripts.js"></script>
	<link href="scripts/greybox/gb_styles.css" rel="stylesheet" type="text/css" />

	<script src="scripts/scriptaculous/prototype.js" type="text/javascript"></script>
	<script src="scripts/scriptaculous/scriptaculous.js" type="text/javascript"></script>
	
	<script>
	function call_back_buscarCli(idCli, gbHide){	
	   document.getElementById('FormCliente').cli_data_id.value=idCli;
	   xajax_loadCli(idCli,gbHide);
	}	
	
	function bodyOnload(){
		formCliDisabled(true);
	}
	</script>
</head>

<body onLoad="bodyOnload()">



<div id="base">
<?php include('menu.php'); ?>

	<div id="principal">
	<h5 class="titulos">Clientes</h5>
		<?php include('cliente_form.php'); ?>

		<div style="width:200px; position: relative; float:left; margin-top:30px; display:none">
		<input style="height: 25px; width:145px" type="button" id="mainbutton" value="Confirmar Reserva" onClick="confirmarRes()" style="margin-left:10px; margin-top:20px;"/>
		</div>

	</div>

	<div id="secundario">
	<h5 class="titulos">&nbsp;</h5>
	</div>

</div>
</body>
</html>