<?php
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_factura.php');

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']))
     exit;

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Presentacion/factura.xajax.req.php');
$xajax->printJavascript('xajax/'); 
?>

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
	var class_id=<?php print factura::$ID ?>;
	var err_numfra=<?php print factura::$ERR_NUMFRA ?>;
	var idFra=0;

	var idLastSelected="";
	function selectFra(idFra){
		if(idLastSelected.length>0)
			document.getElementById(idLastSelected).className="";
		document.getElementById(idFra).className="selected";
		idLastSelected=idFra;
		
		id=idFra.split("_");
		id=id[1];
		xajax_loadLineasFra(id);
	}

	function cerrarFactura(){
		numfra=document.getElementById("formFra").numfra.value;
		if(numfra.length>0){
			document.getElementById("formFra").importe_total.value=document.getElementById("formFootFra").imptotal.value;
			document.getElementById("formFra").id_fra.value=idFra;
			xajax_closeFactura(xajax.getFormValues("formFra"));
		}
		else{
			GB_showCenter('Error', '/view.php?page=message_box&opc='+class_id+'&result='+err_numfra,100,300);
		}
	}

	function bodyOnload(){
		xajax_loadCheckins();
		xajax_loadFrasOpened();
	}
</script>
</head>

<body onLoad="bodyOnload()">	

<div id="base">
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>

<div id="principal">
	<h5 class="titulos">Factura</h5>

 	<div class="box_amarillo" align="center" style="float: left; height: 200px; width: 450px; margin-top:20px">
			<div><span class="label"><b>Pendiente de facturar</b></span></div>
			<div id="ListaCheckins" style="height:150px;overflow:auto">	</div>		
	</div>	

 	<div class="box_amarillo" align="center" style="float: left; height: 200px; width: 450px; margin-top:20px">
			<div><span class="label"><b>Facturas sin cerrar</b></span></div>
			<div id="ListaFras" style="height:150px;overflow:auto">	</div>		
	</div>
	
</div>

<div id="secundario">
	<h5 class="titulos">Datos Factura</h5>
	<div id="datos_pers">
			<div class="box_amarillo" style="width:430px; margin-top:20px">
  			<form id="formFra" name="formFra">
  			<div class="row" align="left">
      		<div style="width:120px;float:left"><span>Nombre:</span></div>
      		<div><span><input id="cli_data_nombre" name="nombre" type="text" size="25" value="<?php print $_POST['nombre'] ?>"/></span></div>
   			</div>
			<div class="row" align="left">
     		<div style="width:120px;float:left"><span>Apellido1:</span></div>
     		<div><span><input id="cli_data_apellido1" name="apellido1" type="text" size="25"  value="<?php print $_POST['apellido1'] ?>"/></span></div>
		    </div>
		    <div class="row" align="left">
     		<div style="width:120px;float:left"><span>Apellido2:</span></div>
     		<div><span><input id="cli_data_apellido2" name="apellido2" type="text" size="25"  value="<?php print $_POST['apellido2'] ?>"/></span></div>
		    </div>
		    <div class="row" align="left">
     		<div style="width:120px;float:left"><span>N.I.T:</span></div>
     		<div><span><input id="nit" name="nit" type="text" size="25"  value=""/></span></div>
		    </div>
		    <div class="row" align="left">
     		<div style="width:120px;float:left"><span>Numero Factura:</span></div>
     		<div><span><input id="numfra" name="numfra" type="text" size="25"  value=""/></span></div>
		    </div>
    		<div class="row" align="left">
     		<div style="width:120px;float:left"><span>Fecha Factura:</span></div>
     		<div><span><input id="fechafra" name="fechafra" type="text" size="9" maxlength="10"  value="<?php print date("d/m/Y") ?>"/></span></div>
		    </div>
			<input id="importe_total" name="importe_total" type="hidden" />
			<input id="id_fra" name="id_fra" type="hidden" value="0" />
			</form>
			</div>
			
		
		
			
			<div class="box_amarillo" style="width: 430px; height: 200px; margin-top:20px">
				<div id="editLineas" style="height:160px;overflow:auto"></div>
	
			</div>
			
			
			<div class="box_amarillo" style="width:70px; margin-top:20px; margin-right:37px; float:right">
				<div id="DatosFactura"></div>
		
				<form id="formFootFra" name="formFootFra">
				<table class="t_general" style="width:100%">
				<tr class="t_row" id="linea_edit" style="background:#fff;">
				<td class="t_col" style="width:60px">Total Factura</td>
				</tr>
				<tr>
				<td><input style="width:100%;text-align:center" id="imptotal" name="imptotal" type="text"  value="" onChange="calcularfra()" disabled="true"/></td>		
				</tr>
				</table>
				</form>
			
				</div>
	
			</div>		
				<div style="float:right; width:250px">			
					<div style="float:left; margin-top:50px">
						<span><input type="button" value="Cerrar Factura" onClick="cerrarFactura()"></span>
					</div>
					
					<!--
					<div style="float:right; margin-top:50px; margin-right:60px">
						<span><input type="button" value="Imprimir Factura" onClick="GB_showCenter('Factura','/view.php?page=print_factura&id='+idfra,450,400);"></span>
					</div>
					-->
				</div>
		
</div>

</div>

</body>
</html>