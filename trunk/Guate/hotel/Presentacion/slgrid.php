<?php
	require ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_listado.php');
	session_start();
	
	//Define where you have placed the grid folder.
    define("GRID_SOURCE", "slgrid/"); 
        
	include(GRID_SOURCE."config.inc.php");     
        include(GRID_SOURCE."class/gridclasses.php"); //Include the grid engine.
        
        //Define identifying name(s) to your grid(s). Must be unqiue name(s).
        $grid_id = array("grid");

        //Remember to comment the again line when publishing PHP Grid, or else PHP Grid wont remember the settings between page loads.
        unset($_SESSION["grid"]); 
        include(GRID_SOURCE."class/gridcreate.php"); //Creates grid objects.
?>
        
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>ajax table search</title>
	<meta name="format" value="text/xhtml">
	
	<!-- ajaxtabelle -->
		<script language="Javascript" type="text/javascript" src="scripts/slgrid.js"></script>
	    <link href="css/slgrid.css" rel="stylesheet" type="text/css">
		<script type='text/javascript' src="Presentacion/<?php print(GRID_SOURCE);?>server.php?client=all"></script>
		<script>
			HTML_AJAX.defaultServerUrl = 'Presentacion/<?php print(GRID_SOURCE);?>server.php';
		</script>
</head>
<body onload="focusForm()">

<script type="text/javascript">
	function setBGCol(pElement, pColor) {
		document.getElementById(pElement).style.background = pColor;
	}
	function changevalue(txt_field,txt_value) {
			<?php
				if($_GET['src']=='reservas'){
					echo 'parent.parent.call_back_buscarRes(txt_value, true);';
				}
				elseif($_GET['src']=='clientes'){
					echo 'parent.parent.call_back_buscarCli(txt_value, true);';
				}
			?>
	}
</script>

<?php
	$_SESSION["grid"]->SetDatabaseConnection($db_database, $db_username, $db_passwort);
	
	if($_GET['mode']=='view'){
		$list= new listado();
		
		$id=$_GET['id'];
		
		if($_GET['f1']!=null && $_GET['f2']!=null){
			list($d, $m, $y, $h)=split("/", $_GET['f1']);
			$f1=implode("-",array($y,$m,$d));
			if(strlen($h)>1)
				$f1.=" ".$h;
			list($d, $m, $y, $h)=split("/", $_GET['f2']);
			$f2=implode("-",array($y,$m,$d));
			if(strlen($h)>1)
				$f2.=" ".$h;
			
			if ($id=="caja"){		
				if(strlen($list->get_condicion($id))>0)
					$cond=$list->get_condicion($id)." and ";
				$cond.="fecha>='".$f1."' AND fecha<='".$f2."'";
				$list->set_condicion($cond,$id);			
			}
			elseif ($id=="hab_res"){
				if(strlen($list->get_condicion($id))>0)
					$cond=$list->get_condicion($id)." and ";
				$cond.="(('$f1' >= fec_ini AND '$f1' <= fec_fin )	
					OR ('$f2' >= fec_ini AND '$f2' <= fec_fin )
					OR ('$f1' < fec_ini AND '$f2' > fec_fin))";
				$list->set_condicion($cond,$id);
				//$_SESSION["grid"]->SetDatabaseColumnWidth('Alojamiento', '170');
				$_SESSION["grid"]->SetDatabaseColumnWidth('Fecha_Inicio', '70');
			}
		}	
	
		echo $id;
		if (isset($f1))
			echo "   ".$f1." hasta ".$f2;
		
		$_SESSION["grid"]->SetOrderColumnIndex($list->get_col_orden($id));
		$_SESSION["grid"]->SetSqlSelect($list->get_select($id),$list->get_tabla($id), $list->get_condicion($id));		
		$_SESSION["grid"]->SetUniqueDatabaseColumn($list->get_id_tabla($id), false);
		$_SESSION["grid"]->SetShowExcelIco(true);
		$_SESSION["grid"]->SetShowPrintIco(true);
		$_SESSION["grid"]->SetMaxRowsEachPage(20);
		$mode=MODE_VIEW;
	}
	else{
		$mode=MODE_EDIT;
		
		if($_GET['src']=='reservas'){
			$_SESSION["grid"]->SetSqlSelect('reserva.Id_res, nombre as Nombre, apellido1 as Apellido, fec_ini as "Fecha_Inicio", fec_fin as "Fecha_Fin", imp_pagado as Pagado','reserva LEFT JOIN cliente ON reserva.Id_cliente=cliente.Id_cliente', 'not exists (Select * from checkin where checkin.Id_res=reserva.Id_res)');
			$_SESSION["grid"]->SetUniqueDatabaseColumn("Id_res", false); // (string id_column_name, boolean show)      
			$_SESSION["grid"]->SetTitleName("Reservas");
			$_SESSION["grid"]->SetOrderColumnIndex(1);
		}
		elseif($_GET['src']=='clientes'){
			//$_SESSION["grid"]->SetSqlSelect('p.id, p.firstname AS Vorname, p.surename AS Nachname, c.name AS Unternehmen, con.name AS Land', 'person p LEFT JOIN company c ON p.company_id=c.ID LEFT JOIN countries con ON p.country_id=con.ID', 'p.surename!="" and p.surename like "'.$person[surename].'%" and p.firstname like "'.$person[firstname].'%" and c.name like "%"');
			$_SESSION["grid"]->SetSqlSelect('Id_cliente, nombre as Nombre, apellido1 as Apellido_1, apellido2 as Apellido_2, pasaporte as Pasaporte','cliente', 'apellido1 like "'.$person[surename].'%" and nombre like "'.$person[firstname].'%"');
	
			$_SESSION["grid"]->SetUniqueDatabaseColumn("Id_cliente", false); // (string id_column_name, boolean show)      
			$_SESSION["grid"]->SetTitleName("Clientes");
			$_SESSION["grid"]->SetDatabaseColumnWidth('nombre', '100');
			$_SESSION["grid"]->SetDatabaseColumnWidth('Apellido1', '120');
			$_SESSION["grid"]->SetDatabaseColumnWidth('apellido2', '120');	
			$_SESSION["grid"]->SetOrderColumnIndex(1);	
		}

	}
	
	$_SESSION["grid"]->PrintGrid($mode);

/*	if($_POST[person_id]!="")
	{	echo "	<script type=\"text/javascript\">
					HTML_AJAX.replace('grid', 'gridajax', 'AjaxShowEditForm', 'grid', '$_POST[person_id]');
				</script>";

	}

*/

?>

</body>
</html>