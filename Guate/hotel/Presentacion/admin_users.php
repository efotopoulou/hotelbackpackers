<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/Presentacion/admin_users.xajax.req.php');
$xajax->printJavascript('xajax/');


?>
	
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<meta name="MSSmartTagsPreventParsing" content="true">
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

	<script src="/common/js/jquery-1.2.3.pack.js"></script>
	<script src="/common/js/jquery.blockUI.js"></script>
	<script src="scripts/nav.js"></script>	

	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript">
	    var GB_ROOT_DIR = "/hotel/scripts/greybox/";
	</script>
	
	<script type="text/javascript" src="scripts/greybox/AJS.js"></script>
	<script type="text/javascript" src="scripts/greybox/gb_scripts.js"></script>
	<link href="scripts/greybox/gb_styles.css" rel="stylesheet" type="text/css" />

	<script src="scripts/scriptaculous/prototype.js" type="text/javascript"></script>
	<script src="scripts/scriptaculous/scriptaculous.js" type="text/javascript"></script>
	
	<script>
	
		var idUserLast="";
		var modo=0;
		function selectUser(idUser){
			if(idUserLast.length>0)
				document.getElementById(idUserLast).className="";
			document.getElementById(idUser).className="selected";
			idUserLast=idUser;
			id=idUser.split("_");
			document.getElementById('user_id').value=id[1];
			document.getElementById('user_idperfil').value=document.getElementById(idUser).cells[1].id;
			select=document.getElementById('user_perfiles');
			for(i=0;i<select.length;i++){
				if(select.options[i].value==document.getElementById('user_idperfil').value){
					select.options[i].selected=true;
					break;
				}
			}
			copyRowToEdit(idUser,'user_edit' );
		}
		
		function optionUser(opc){
			if(opc==1 || opc==2){	//a�adir-modificar
				if(opc==1)
					document.getElementById('FormUsers').reset();	
				document.getElementById('user_edit').style.display="";
				modo=opc;  
				showButtons('b1', 'b2');
			}
			else if(opc==3){	//eliminar
				modo=3; 
				xajax_changeUsers(xajax.getFormValues('FormUsers'), modo);
			}
		}	
		
		function copyIdPerfil(){
			elect=document.getElementById('user_perfiles').selectedIndex; 
			if(elect>=0){
				id=parseInt(document.getElementById('user_perfiles').options[elect].value);
	   			document.getElementById('user_idperfil').value=id;
			}
		}
		
		var idPerfilLast="";
		function selectPerfil(idPerfil){
			if(idPerfilLast.length>0)
				document.getElementById(idPerfilLast).className="";
			document.getElementById(idPerfil).className="selected";
			idPerfilLast=idPerfil;
			id=idPerfil.split("_");
			document.getElementById('perfil_id').value=id[1];
			
			row=document.getElementById(idPerfil);
			document.getElementById('perfil_edit').cells[0].firstChild.value=row.cells[0].innerHTML;
			for(j=1; j<row.childNodes.length; j++){
				document.getElementById('perfil_edit').cells[j].firstChild.checked=row.cells[j].firstChild.checked;
			}
		}	

		function selectPerfilRest(idPerfil){
			if(idPerfilLast.length>0)
				document.getElementById(idPerfilLast).className="";
			document.getElementById(idPerfil).className="selected";
			idPerfilLast=idPerfil;
			id=idPerfil.split("_");
			document.getElementById('perfil_id_rest').value=id[2];
			
			row=document.getElementById(idPerfil);
			document.getElementById('perfil_edit_rest').cells[0].firstChild.value=row.cells[0].innerHTML;
			for(j=1; j<row.childNodes.length; j++){
				document.getElementById('perfil_edit_rest').cells[j].firstChild.checked=row.cells[j].firstChild.checked;
			}
			
		}	

		function optionPerfil(opc){
			if(opc==1 || opc==2){	//a&ntilde;adir-modificar
				if(opc==1)
					document.getElementById('FormPerfil').reset();	
				document.getElementById('perfil_edit').style.display="";
				modo=opc;  
				showButtons('b3', 'b4');
			}
			else if(opc==3){	//eliminar
				modo=3; 
				xajax_changePerfiles(xajax.getFormValues('FormPerfil'), modo);
				xajax_changePerfiles(xajax.getFormValues('FormPerfilRest'), modo);
			}
		}

		function optionPerfilRest(opc){
			if(opc==1 || opc==2){	//a�adir-modificar
				if(opc==1)
					document.getElementById('FormPerfilRest').reset();	
				document.getElementById('perfil_edit_rest').style.display="";
				modo=opc;  
				showButtons('b3_rest', 'b4_rest');
			}
			else if(opc==3){	//eliminar
				modo=3; 
				xajax_changePerfiles(xajax.getFormValues('FormPerfil'), modo);
				xajax_changePerfiles(xajax.getFormValues('FormPerfilRest'), modo);
			}
		}
		
		function guardarPerfil(){
			var data=new Array();
			
			row=document.getElementById('perfil_edit');
			data['perfil_id']=document.getElementById('perfil_id').value;
			data[row.cells[0].firstChild.id]=row.cells[0].firstChild.value;
			data[row.cells[1].firstChild.id]=row.cells[1].firstChild.value;
			cols=11;
			for(j=2; j<12; j++){
				input=row.cells[j].firstChild;
				var checked=input.checked;
				if(checked)
					data[row.cells[j].firstChild.id]=1;
				else
					data[row.cells[j].firstChild.id]=0;
			}
			xajax_changePerfiles(data, modo);
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
	</script>
</head>

<body onLoad="xajax_loadUsers(); xajax_loadPerfiles();xajax_loadPerfilesRest()">

<div id="base">
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>

	<div id="principal">
	<h5 class="titulos">Usuarios del Hotel Backpackers</h5>		
	 	<div class="box_amarillo" align="center" style="overflow:auto;float: left; height:75%; width: 92%; margin-top:20px">
		
			<div id="EditUsers" style="margin-top:20px;overflow:auto"></div>
			
			<form id="FormUsers">
			<table class="t_general">
			<tr class="t_row" id="user_edit" style="background:#fff;display:none">
			<td class="t_col" style="width:70px"><input style="width:70px" id="user_nombre" name="user_nombre" type="text"  value=""/></td>
			<td class="t_col" style="width:100px;"><select style="font-size:10px;width:100px" id="user_perfiles"></select></td>
			<td class="t_col" style="width:100px"><input style="width:100px" id="password" name="password" type="text"  value=""/></td>
			<td class="t_col" style="width:100px"><input style="width:100px" id="email" name="email" type="text"  value=""/></td>
			</tr>
			</table>
			<input id="user_id" name="user_id" type="hidden"  value=""/>
			<input id="user_idperfil" name="user_idperfil" type="hidden"  value=""/>
			</form>
			
			<div id="b1" style="float:left; width:100%; margin-top:5px;">			
				<input type="button" value="A&ntilde;adir"  style="width:100px" onclick="optionUser(1);"/>
				<input type="button" value="Modificar" style="width:100px" onclick="optionUser(2)"/>
				<input type="button" value="Eliminar" style="width:100px" onclick="optionUser(3)"/>		
			</div>
			
			<div id="b2" style="float:left; width:100%; margin-top:5px; display:none">			
				<input type="button" value="Guardar"  style="width:100px" onclick="copyIdPerfil(); xajax_changeUsers(xajax.getFormValues('FormUsers'), modo);document.getElementById('user_edit').style.display='none'; "/>
				<input type="button" value="Cancelar" style="width:100px" onclick="document.getElementById('user_edit').style.display='none'; showButtons('b2', 'b1')"/>	
			</div>
		</div>	
		
		
			
	</div>

	<div id="secundario">
	<h5 class="titulos">Perfiles de los usuarios</h5>
	
	
	<div class="box_amarillo" align="center" style="float: left; height:50%; width: 435px; margin-top:20px">
		Perfiles Hotel:<br /><br />
			<form id="FormPerfil">
			<div id="EditPerfil" style="height200px;overflow:auto"></div>
			<input id="perfil_id" name="perfil_id" type="hidden"  value=""/>
			</form>
			
			<div id="b3" style="float:left; width:100%; margin-top:5px;">			
				<input type="button" value="A&ntilde;adir"  style="width:100px" onclick="optionPerfil(1)"/>
				<input type="button" value="Modificar" style="width:100px" onclick="optionPerfil(2)"/>
				<input type="button" value="Eliminar" style="width:100px" onclick="optionPerfil(3)"/>		
			</div>
			
			<div id="b4" style="float:left; width:100%; margin-top:5px; display:none">			
				<input type="button" value="Guardar"  style="width:100px" onclick="guardarPerfil()"/>
				<input type="button" value="Cancelar" style="width:100px" onclick="document.getElementById('perfil_edit').style.display='none'; showButtons('b4', 'b3')"/>	
			</div>
        <br /><br /><br /><br />
		Perfiles Recepcion-Restaurante-Bar:<br /><br />
			<form id="FormPerfilRest">
			<div id="EditPerfilRest" style="height200px;overflow:auto"></div>
			<input id="perfil_id_rest" name="perfil_id_rest" type="hidden"  value=""/>
			</form>
			
			<div id="b3_rest" style="float:left; width:100%; margin-top:5px;">			
				<input type="button" value="A&ntilde;adir"  style="width:100px" onclick="optionPerfilRest(1)"/>
				<input type="button" value="Modificar" style="width:100px" onclick="optionPerfilRest(2)"/>
				<input type="button" value="Eliminar" style="width:100px" onclick="optionPerfilRest(3)"/>		
			</div>
			
			<div id="b4_rest" style="float:left; width:100%; margin-top:5px; display:none">			
				<input type="button" value="Guardar"  style="width:100px" onclick="guardarPerfil()"/>
				<input type="button" value="Cancelar" style="width:100px" onclick="document.getElementById('perfil_edit_rest').style.display='none'; showButtons('b4_rest', 'b3_rest')"/>	
			</div>

		</div>
	
	
	
	</div>

</div>
</body>
</html>