
<?php

include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_factura.php');

$idfra=$_GET['id'];

$fra=new factura();
$fra->get_factura($idfra);
$nombre=$fra->get_nombre();
$fecha=$fra->get_fechafra();
$nit=$fra->get_nit();
$fecha=split("-",$fecha);
$dia=$fecha[2];
$mes=$fecha[1];
$any=$fecha[0];
$res=$fra->get_lineas($idfra);


?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<title></title><!-- Meta Information -->
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta name="MSSmartTagsPreventParsing" content="true">
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	
				
	<script type="text/javascript">
	    var GB_ROOT_DIR = "/hotel/scripts/greybox/";
	</script>
	
	<script type="text/javascript" src="scripts/greybox/AJS.js"></script>
	<script type="text/javascript" src="scripts/greybox/gb_scripts.js"></script>
	<link href="scripts/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
	
</script>

</head>


<body>

<table class='t_general fondo_tabla' style='width:100%'>

	<table>
		<tr>
		<td><?php print $dia?></td>
		<td><?php print $mes?></td>
		<td><?php print $any?></td>
		</tr>
	</table>

	<table>
		<tr>
		<td colspan="2"><?php print $nombre?></td>
		</tr>
		<tr>
		<td></td>
		<td><?php print $nit?></td>
		</tr>
	</table>
<?php printlineasfra($fra)?>
</table>




<?php

 	function printlineasfra($fra){
 		$total=0;	
 		print "<table>";
  		print "<tr style='background:#fff'>";
  		print "<td style='width:60px; text-align:center''>Cantidad</td>";
  		print "<td style='width:150px'>Descripción</td>";
  		print "<td style='width:60px; text-align:center'>Importe</td>";
  		print "</tr>";
  		
  		if($fra->get_count()>0)
  		do{
  			print"<tr>";
  			print"<td style='width:60px; text-align:center'>".$fra->get_noches()."</td>";
  			print"<td style='width:60px; text-align:center'>".$fra->get_descripcion()."</td>";
  			print"<td style='width:60px; text-align:center'>".$fra->get_importe()."</td>";
			print"</tr>";
  		$total=$total+$fra->get_importe();
  		}while($fra->movenext());
  		
  		print "<tr>";
  		print "<td>$total</td>";
  		print "</td>";
		print "</table>";
		
		
  	}
  	
?>	

</body>
</html>
	
	
	