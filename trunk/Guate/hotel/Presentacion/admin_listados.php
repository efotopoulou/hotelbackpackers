<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_listado.php');

?>
	
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<meta name="MSSmartTagsPreventParsing" content="true">
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

	<link href="css/estilo.css" rel="stylesheet" type="text/css" />
	<link href="scripts/calendar-green.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/calendar.js"></script>
	<script type="text/javascript" src="scripts/calendar-sp.js"></script>
	<script type="text/javascript" src="scripts/calendar-setup.js"></script>
		
	<script src="scripts/nav.js"></script>	

	<link href="estilo.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript">
	    var GB_ROOT_DIR = "/scripts/greybox/";
	</script>
	
	<script type="text/javascript" src="scripts/greybox/AJS.js"></script>
	<script type="text/javascript" src="scripts/greybox/gb_scripts.js"></script>
	<link href="scripts/greybox/gb_styles.css" rel="stylesheet" type="text/css" />

	<script src="scripts/scriptaculous/prototype.js" type="text/javascript"></script>
	<script src="scripts/scriptaculous/scriptaculous.js" type="text/javascript"></script>
	
	<script>
		var err_hora=<?php print listado::$ERR_HORA ?>;
		var class_id=<?php print listado::$ID ?>;
		
		var idLastSelected="";
		function selectListado(id){
			if(idLastSelected.length>0)
				document.getElementById(idLastSelected).className="";
			document.getElementById(id).className="selected";
			idLastSelected=id;
							
		}
	
		function loadListado(){
			if(idLastSelected=='caja'){		//listados con fecha y hora
				if(document.getElementById("f_date_c").value.length>0 && document.getElementById("f_date_c1").value.length>0 && document.getElementById("horaini").value.length>0 && document.getElementById("horafin").value.length>0){
					var f1=document.getElementById("f_date_c").value+"/"+document.getElementById("horaini").value+":"+document.getElementById("minini").value;
					var f2=document.getElementById("f_date_c1").value+"/"+document.getElementById("horafin").value+":"+document.getElementById("minfin").value;;
					return GB_showCenter('Listado', '/view.php?page=slgrid&mode=view&id='+idLastSelected+'&f1='+f1+'&f2='+f2,450, 800);
				}
				else
					GB_showCenter('Error', '/view.php?page=message_box&opc='+class_id+'&result='+err_hora,100,300);
			}
			else{
				
				if(document.getElementById("f_date_c").value.length>0 && document.getElementById("f_date_c1").value.length>0){
					var f1=document.getElementById("f_date_c").value+"/"+document.getElementById("horaini").value+":"+document.getElementById("minini").value;
					var f2=document.getElementById("f_date_c1").value+"/"+document.getElementById("horafin").value+":"+document.getElementById("minfin").value;
					return GB_showCenter('Listado', '/view.php?page=slgrid&mode=view&id='+idLastSelected+'&f1='+f1+'&f2='+f2,450, 800);
				}
				else
					return GB_showCenter('Listado', '/view.php?page=slgrid&mode=view&id='+idLastSelected,450, 800);		
			}
			
			
			
		}
					
		function listCaja(){
			if(document.getElementById("f_date_c").value.length>0 && document.getElementById("f_date_c1").value.length>0){
				var f1=document.getElementById("f_date_c").value+"/"+document.getElementById("horaini").value+":"+document.getElementById("minini").value;
				var f2=document.getElementById("f_date_c1").value+"/"+document.getElementById("horafin").value+":"+document.getElementById("minfin").value;;
				return GB_showCenter('Listado', '/view.php?page=slgrid&mode=view&id=caja&f1='+f1+'&f2='+f2,450, 800);
			}
			else{
				return GB_showCenter('Listado', '/view.php?page=slgrid&mode=view&id=caja',450, 800);
			}
		}
		
	</script>
</head>

<body onLoad="selectListado('caja')">

<div id="base">
<?php include('menu.php'); ?>

	<div id="principal">
	<h5 class="titulos">Listados</h5>		
	 	<div class="box_amarillo" align="center" style="float: left; height: 75px; width: 435px; margin-top:20px">
		
 			<form id="fechascaja" name="fechascaja">
			<div class="row" align="left">
      		<div style="width:90px;float:left"><span>Fecha Inicio:</span></div>
			<div><span><input type="text" name="date_a" id="f_date_c" maxlength="10" size="9" value="<?php print $fecini;?>" onChange="date_b.value=this.value"/>    
			<img src="img/calendar.gif" align="absbottom"; id="f_trigger_c"; title="Date Selector"; style="cursor:pointer; border:1px solid green">
			<script type="text/javascript">	
			Calendar.setup({
			inputField : "f_date_c",
			ifFormat : "%d/%m/%Y",
			daFormat : "%d/%m/%y",
			firstDay: 1,
			button : "f_trigger_c",
			align : "Tr",
			singleClick : true
			});
			</script>						
			</span>
			
			<span style="margin-left:20px">Hora:</span>
     		<input type="text" name="horaini" id="horaini" size="1" maxlength="2" />:<input type="text" name="minini" id="minini" size="1" maxlength="2" />
     		</div>
   			</div>
    		
			<div class="row" align="left">
     		<div style="width:90px;float:left"><span>Fecha Fin:</span></div>
			<div><span><input type="text" name="date_b" id="f_date_c1" maxlength="10" size="9"  value="<?php print $fecfin;?>"/>
			<img src="img/calendar.gif" align="absbottom"; id="f_trigger_c1"; title="Date Selector"; style="cursor:pointer; border:1px solid green">			<script type="text/javascript">
			Calendar.setup({
			date: "null",
			inputField : "f_date_c1",
			ifFormat : "%d/%m/%Y",
			daFormat : "%d/%m/%y",
			firstDay: 1,
			button : "f_trigger_c1",
			align : "Tr",
			singleClick : false
			});
			</script>			
			</span>
			     		
     		<span style="margin-left:20px">Hora:</span>
     		<input type="text" name="horafin" id="horafin" size="1" maxlength="2" />:<input type="text" name="minfin" id="minfin" size="1" maxlength="2" />
     		</div>
     		</div>
   				
    		</form> 
		</div>
		
		<div class="box_amarillo" align="center" style="float: left; height: 155px; width: 435px; margin-top:20px">		
			<?php echo genera_tabla_listados() ?>
	
			<div style="float:left; width:100%; margin-top:5px;">
			<input type="button" value="Enviar" style="width:100px" onclick="loadListado()"/>
			</div>	
		</div>	
		
						
	</div>

	<div id="secundario">
		<h5 class="titulos">&nbsp;</h5>
	</div>

</div>
</body>
</html>

<?php
	function genera_tabla_listados(){
		$listado=new listado();
		
		$html= '<table class="t_general fondo_tabla" style="width:100%">';
		$html.= '<tr class="t_row" style="background:#fff">' .
					'<td class="t_col" style="width:70px">Nombre</td>' .
					'<td class="t_col" style="width:250px; text-align:center">Descripción</td></tr>';
		
		if($listado->get_count()){
			do{
				$html.= '<tr class="t_row" id="'.$listado->get_id().'" style="cursor:pointer" onclick="selectListado(this.id)">' .
					'<td class="t_col">'.$listado->get_id().'</td>' .
					'<td class="t_col" style="text-align:center">'.$listado->get_descripcion().'</td></tr>';
			}while($listado->movenext());
		}
		$html.='</table>';
		
		return $html;
	}
?>