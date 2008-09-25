<?php
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_temporada.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_habitaciones.php');



require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Presentacion/admin_precios.xajax.req.php');
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
	
	var ancho=10;
	var alto=10;
	var sep=1;
	creaPaleta=function(element,callBack){
		var clip,col,top,left;
		var aColors=["00","33","66","99","CC","FF"];
		for(var x=0;x<6;x++){
			for(var y=0;y<6;y++){
				for(var z=0,d=0;z<6;z++,d++){
					col="#"+aColors[z]+aColors[x]+aColors[y];
					clip=document.createElement("a");
					clip.href="Color: "+col;
					clip.funcio=callBack;
					clip.onclick=function(){
						this.funcio(this.color);
						return false;
					}
					cs=clip.style;
					cs.backgroundColor=clip.color=col;
					top=sep+y*(alto+sep);
					cs.top=top+"px";
					left=sep+(z*((sep+ancho)*6))+(x*(ancho+sep));
					cs.left=left+"px";
					cs.width=ancho+"px";
					cs.height=alto+"px";
					cs.position="absolute";
					cs.overflow="hidden";
					element.appendChild(clip);
				}
			}
		}
		element.style.position="relative";
		element.style.height=(6*(alto+sep)+1)+"px";
		element.style.width=(6*6*(ancho+sep)+1)+"px";
	}
	function funcionRetorno(e){
		document.getElementById("taloj_color").value=e;
		document.getElementById("Paleta").style.display="none";
	}
		
	function showLiteras(idParent){	
		
		if(document.getElementById("container_"+idParent).style.display == ""){			
			document.getElementById("container_"+idParent).style.display="none";
			document.getElementById("aloj_"+idParent).firstChild.style.backgroundImage="url(/img/arrow_d.gif)";
		}
		else{
			document.getElementById("aloj_"+idParent).firstChild.style.backgroundImage="url(/img/ajax-loader.gif)";
			xajax_loadEditAlojs(idParent);	
		}
	}
	
	var idLastSelected="";
	var modo=0;
	function selectTempo(idTempo){
		if(idLastSelected.length>0)
			document.getElementById(idLastSelected).className="";
		if(idTempo.length>0){
			document.getElementById(idTempo).className="selected";
			idLastSelected=idTempo;
			id=idTempo.split("_");
			id=id[1];
			copyRowToEdit(idTempo,'tempo_edit' );	
			}
		else
			id=0;
		document.getElementById('FormTempo').id_tempo.value=id;		
	}

	var idAlojLast="";
	function selectAloj(idAloj){
		if(idAlojLast.length>0)
			document.getElementById(idAlojLast).className="";
		document.getElementById(idAloj).className="selected";
		idAlojLast=idAloj;
		id=idAloj.split("_");
		document.getElementById('id_aloj').value=id[1];
		
		copyRowToEdit(idAloj,'aloj_edit' );	
		
		document.getElementById('aloj_edit').cells[0].firstChild.style.background=document.getElementById(idAloj).cells[0].style.background;
		document.getElementById('aloj_edit').cells[0].firstChild.style.backgroundImage='';		
		document.getElementById('FormAlojs').aloj_tipo.value=document.getElementById(idAloj).cells[0].id;
		document.getElementById('aloj_idparent').checked=document.getElementById(idAloj).cells[3].firstChild.checked;
	}
	
	var idTipoAlojLast="";
	function selectTipoAloj(idTipo){
		if(idTipoAlojLast.length>0)
			document.getElementById(idTipoAlojLast).className="";
		document.getElementById(idTipo).className="selected";
		idTipoAlojLast=idTipo;
		id=idTipo.split("_");
		document.getElementById('id_taloj').value=id[1];
		
		copyRowToEdit(idTipo,'taloj_edit' );
	}
	
	var idTipoPrecioLast="";
	function selectTipoPrecio(idTipo){
		if(idTipoPrecioLast.length>0)
			document.getElementById(idTipoPrecioLast).className="";
		document.getElementById(idTipo).className="selected";
		idTipoPrecioLast=idTipo;
		id=idTipo.split("_");
		id=id[1];
		xajax_loadAlojPrecios(id);
		//document.getElementById('id_tp').value=id;
		//copyRowToEdit(idTipo,'taloj_edit' );
	}
	
	
	function copyRowToEdit(idrow, idEditRow){
		row=document.getElementById(idrow);
		for(j=0; j<row.childNodes.length; j++){
			document.getElementById(idEditRow).cells[j].firstChild.value=row.cells[j].innerHTML;
		}	
	}
		
	function showButtons(b1, b2){
	   		document.getElementById(b1).style.display = "none";
			document.getElementById(b2).style.display = "";
	}	
	
	function optionTipoAloj(opc){
		if(opc==1 || opc==2){	//añadir-modificar
			if(opc==1)
				document.getElementById('FormTipoAlojs').reset();	
			document.getElementById('taloj_edit').style.display="";
			document.getElementById('taloj_edit').cells[0].firstChild.focus();
			modo=opc;  
			showButtons('b5', 'b6');
		}
		else if(opc==3){	//eliminar
			modo=3; 
			xajax_changeTipoAlojs(xajax.getFormValues('FormTipoAlojs'), modo);
		}
		
	}
	
	function optionAloj(opc){
		if(opc==1 || opc==2){	//añadir-modificar
			if(opc==1){
				document.getElementById('FormAlojs').reset();	
				document.getElementById('FormAlojs').aloj_tipo.value=0;			
				document.getElementById('aloj_nombre').style.background='';
			}
			document.getElementById('aloj_edit').style.display="";
			document.getElementById('SelectTipo').style.display='';
			document.getElementById('aloj_edit').cells[0].firstChild.focus();
			modo=opc;  
			showButtons('b3', 'b4');
		}
		else if(opc==3){	//eliminar
			modo=3; 
			xajax_changeAlojs(xajax.getFormValues('FormAlojs'), modo);
		}
		
	}
	
	function optionTempo(opc){
		if(opc==1 || opc==2){	//añadir-modificar
			if(opc==1)
				document.getElementById('FormTempo').reset();	
			document.getElementById('tempo_edit').style.display="";
			document.getElementById('tempo_edit').cells[0].firstChild.focus();
			modo=opc;  
			showButtons('b1', 'b2');
		}
		else if(opc==3){	//eliminar
			modo=3; 
			xajax_changeTempos(xajax.getFormValues('FormTempo'), modo)
		}
		
	}
	
	function copyTipo(idTipo, color){	
		document.getElementById('aloj_tipo').value=idTipo;
		document.getElementById('aloj_nombre').style.background=color;
	}
	
	function bodyOnLoad(){
		xajax_loadTemporadas();
		xajax_loadEditAlojs();
		xajax_loadEditTipoAlojs();
		xajax_loadTipoAlojPrecios();
		xajax_loadAlojPrecios();
		creaPaleta(document.getElementById('Paleta'),funcionRetorno);
	}
	
	</script>
</head>
<body onLoad="bodyOnLoad()">

<div id="base">
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>

<div id="principal">
<h5 class="titulos">Alojamientos</h5>
	<div class="box_amarillo" align="center" style="float: left; height: 275px; width:400px;  margin-top:20px; margin-right:20px">
		
		<div id="EditAlojs" style="height:200px;overflow:auto"></div>
		
		<form id="FormAlojs">
		<table class="t_general" style="">
		<tr class="t_row" id="aloj_edit" style="background:#fff;display:none">
		<td class="t_col" style="width:100px"><input style="width:70px" id="aloj_nombre" name="aloj_nombre" type="text"  value=""/></td>
		<td class="t_col" style="width:80px;"><input style="width:78px;text-align:right" id="aloj_matrim" name="aloj_matrim" type="text" value=""/></td>
		<td class="t_col" style="width:80px;"><input style="width:78px;text-align:right" id="aloj_indiv" name="aloj_indiv" type="text"  value=""/></td>
		<td class="t_col" style="width:60px;"><input style="width:65px;text-align:center" id="aloj_idparent" name="aloj_idparent" type="checkbox"/></td>
		<td class="t_col" style="width:40px;"><input style="width:40px;text-align:right" id="aloj_orden" name="aloj_orden" type="text"  value=""/></td></tr>
		</table>
		<input id="aloj_tipo" name="aloj_tipo" type="hidden"  value="0"/>
		<input id="id_aloj" name="id_aloj" type="hidden"  value=""/>
		</form>
		
		
		
		<div id="b3" style="float:left; width:100%; margin-top:5px">			
			<input type="button" value="Añadir"  style="width:100px" onclick="optionAloj(1);"/>
			<input type="button" value="Modificar" style="width:100px" onclick=" copyRowToEdit(idAlojLast,'aloj_edit' ); optionAloj(2)"/>
			<input type="button" value="Eliminar" style="width:100px" onclick="optionAloj(3)"/>		
		</div>
		
		<div id="b4" style="float:left; width:100%; margin-top:5px; display:none">			
			<input type="button" value="Guardar"  style="width:100px" onclick="xajax_changeAlojs(xajax.getFormValues('FormAlojs'), modo)"/>
			<input type="button" value="Cancelar" style="width:100px" onclick="document.getElementById('aloj_edit').style.display='none'; document.getElementById('SelectTipo').style.display='none'; showButtons('b4', 'b3')"/>	
		</div>

		<div id="SelectTipo" class="t_row" style="z-index:20; position:absolute; top:300px; left:48px; height:50px; width:68px; overflow:auto; display:none">
		</div>
		
	</div>
	<div class="box_amarillo" align="center" style="float: left; height: 150px; width:400px;  margin-top:20px; margin-right:20px">
		
		<div id="EditTipoAlojs" style="height:100px;overflow:auto"></div>
		
		<form id="FormTipoAlojs">
		<table class="t_general" style="width:210px">
		<tr class="t_row" id="taloj_edit" style="background:#fff;display:none">
		<td class="t_col" style="width:60px"><input style="width:60px;text-align:center" id="taloj_color" name="taloj_color" type="text" maxlength="7" value=""/></td>		
		<td class="t_col" style="width:150px"><input style="width:150px" id="taloj_desc" name="taloj_desc" type="text"  value=""/></td></tr>
		</table>
		<input id="id_taloj" name="id_taloj" type="hidden"  value=""/>
		</form>
		
		<div id="b5" style="float:left; width:100%; margin-top:5px;">			
			<input type="button" value="Añadir"  style="width:100px" onclick="optionTipoAloj(1)"/>
			<input type="button" value="Modificar" style="width:100px" onclick="copyRowToEdit(idTipoAlojLast,'taloj_edit' ); optionTipoAloj(2)"/>
			<input type="button" value="Eliminar" style="width:100px" onclick="optionTipoAloj(3)"/>		
		</div>
		
		<div id="b6" style="float:left; width:100%; margin-top:5px; display:none">			
			<input type="button" value="Colores"  style="width:100px" onclick="document.getElementById('Paleta').style.display=''"/>
			<input type="button" value="Guardar"  style="width:100px" onclick="xajax_changeTipoAlojs(xajax.getFormValues('FormTipoAlojs'), modo)"/>
			<input type="button" value="Cancelar" style="width:100px" onclick="document.getElementById('taloj_edit').style.display='none'; document.getElementById('Paleta').style.display='none'; showButtons('b6', 'b5')"/>	
		</div>

		<div id="Paleta" style="z-index:30; position:absolute; background:black; top:-90px; display:none"> 
		</div>
		
	</div>		 
			 
</div>

<div id="secundario">
	<h5 class="titulos">Temporadas y Precios</h5>
	 	<div class="box_amarillo" align="center" style="float: left; height: 250px; width: 250px; margin-top:20px">
				<div><span class="label"><b>Temporadas</b></span></div>
				<div id="EditTempos">
			
				</div>
				
				<div id="b1" style="float:left; width:100%; margin-top:5px;">			
					<input type="button" value="Añadir"  style="width:100px" onclick="optionTempo(1)"/>
					<input type="button" value="Modificar" style="width:100px" onclick="optionTempo(2)"/>
					<input type="button" value="Eliminar" style="width:100px" onclick="optionTempo(3)"/>		
				</div>
				
				<div id="b2" style="float:left; width:100%; margin-top:5px; display:none">			
					<input type="button" value="Guardar"  style="width:100px" onclick="xajax_changeTempos(xajax.getFormValues('FormTempo'), modo)"/>
					<input type="button" value="Cancelar" style="width:100px" onclick="document.getElementById('tempo_edit').style.display='none'; showButtons('b2', 'b1')"/>	
				</div>
		</div>	
		
		<div class="box_amarillo" align="center" style="float: right; height: 350px; width: 100px; margin-top:20px; margin-right:50px">
			<div id="EditTipoPrecios">
			
			</div>
			
			<div><span class="label"><b>Precios</b></span></div>
			<div id="aloj_left">
				<div style="width:100%;height:300px;margin-top:5px;overflow:auto">
					<form id="FormPrecios">
					
					</form>			
		  		</div>
		  	<input type="button" value="Guardar"  style="width:100px; margin-top:5px;" onclick="xajax_insertarPrecios(xajax.getFormValues('FormPrecios'), document.getElementById('id_tempo').value)"/>
		  	</div>
		</div>	
		
</div>

</div>


<?php
/*	
	function genera_left($habit){			
		echo '<table class="t_general">';
		if($habit->get_count())
		do{
			$camas=($habit->get_num_matrim()==0)?$habit->get_num_indiv():($habit->get_num_matrim()."+".$habit->get_num_indiv());
			$title='tipo: '.$habit->get_tipo().', camas: '.$camas;
			echo '<tr class="t_row"><td id="alojLeft_'.$habit->get_id().'" class="t_col_habit" style="background-color:'.$habit->get_color().'" title="'.$title.'">'.$habit->get_nombre().'</td>' .
					'<td><input id="precio_'.$habit->get_id().'" name="precio_'.$habit->get_id().'" style="text-align:right" type="text" size="1" /></td></tr>';
		}while($habit->movenext());
		echo '</table>';
	}*/ 
?>
