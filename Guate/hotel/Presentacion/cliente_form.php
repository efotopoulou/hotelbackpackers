<script>
	xajax_load_autopaises();
	
	function formCliDisabled(value) {
	    document.getElementById("mainbutton").disabled=!value;
	    var limit = document.getElementById("FormCliente").elements.length;
	    for (i=0;i<limit;i++) {
	      document.getElementById("FormCliente").elements[i].disabled = value;
	    }
	}


		var lastIdCli=0;
		function optioncli(opc){
			if(opc==1 || opc==2){	//añadir-modificar
				lastIdCli=document.getElementById("FormCliente").cli_data_id.value;
				if(opc==1)
					document.getElementById("FormCliente").reset(); 
				modo=opc; 
				formCliDisabled(false); 
				showButtons('b5', 'b6');
				document.getElementById("FormCliente").cli_data_nombre.focus();
			}
			else if(opc==3){	//eliminar
				modo=3; 
				xajax_changeCli(xajax.getFormValues('FormCliente',true), modo);
			}
			else if(opc==4){	//cancelar
				if(lastIdCli>0){
					xajax_loadCli(lastIdCli,false);
				}
				else
					document.getElementById('FormCliente').reset();
				formCliDisabled(true); 
				showButtons('b6', 'b5');
			}	
		}
		
		function showButtons(b1, b2){
	   		document.getElementById(b1).style.display = "none";
			document.getElementById(b2).style.display = "";
		}
	
</script>

<div class="box_amarillo" align="center" style="width: 310px;height:350px;margin-top:20px">
			<form id="FormCliente">
			<div>
			<div class="row">
      		<span class="label">Nombre:</span><span class="formw"><input id="cli_data_nombre" name="cli_data_nombre" type="text" size="25" value="" disabled/></span>
   			</div>
    		<div class="row">
     		<span class="label">Apellido1:</span><span class="formw"><input id="cli_data_apellido1" name="cli_data_apellido1" type="text" size="25"  value="" disabled/></span>
		    </div>
		    <div class="row">
     		<span class="label">Apellido2:</span><span class="formw"><input id="cli_data_apellido2" name="cli_data_apellido2" type="text" size="25"  value="" disabled/></span>
		    </div>
		    <div class="row">
      		<span class="label">Pasaporte:</span><span class="formw"><input id="cli_data_pasaporte" name="cli_data_pasaporte" type="text" size="25" disabled/></span>
   			</div>
    		<div class="row">
      		<span class="label">Direcci&oacute;n:</span><span class="formw"><input id="cli_data_direc" name="cli_data_direc" type="text" size="25"  value="" disabled/></span>
   			</div>
		    <div class="row">
      		<span class="label">Poblaci&oacute;n:</span><span class="formw"><input id="cli_data_pob" name="cli_data_pob" type="text" size="25"  value="" disabled/></span>
	   		</div>
			<div class="row">
      		<span class="label">Pa&iacute;s:</span><span class="formw"><input id="cli_data_pais" name="cli_data_pais" autocomplete="off" size="25" type="text" value="" disabled/></span>
	   		<div class="auto_complete" id="lista_paises" style="display:none"></div>
	   		</div>
			<div class="row">
      		<span class="label">Tel&eacute;fono1:</span><span class="formw"><input id="cli_data_tel1" name="cli_data_tel1" type="text" size="25"  value="" disabled/></span>
	   		</div>
			<div class="row">
      		<span class="label">Tel&eacute;fono2:</span><span class="formw"><input id="cli_data_tel2" name="cli_data_tel2" type="text" size="25"  value="" disabled/></span>
	   		</div>
			<div class="row">
      		<span class="label">e-mail:</span><span class="formw"><input id="cli_data_mail" name="cli_data_mail" type="text" size="25"  value="" disabled/></span>
	   		</div>
			</div>
			<div class="row">
      		<span class="label">Observaciones:</span>
	   		</div>
	   		<div class="row">
      		<span class="formw"><textarea id="cli_data_observ" name="cli_data_observ" rows="4" cols="28" disabled><?php print $observ;?></textarea></span>
	   		</div>
	   		<input id="cli_data_id" name="cli_data_id" type="hidden" value="0"/>
  			</form>
</div>


		
	<div id="b5" style="float:left; width:310px; margin-top:5px">	
		<input type="button" value="Buscar" style="width:70px" onclick="formCliDisabled(true); return GB_showCenter('Clientes', '/hotel/view.php?page=slgrid&src=clientes' ,200, 600)"/>	
		<input type="button" value="Añadir"  style="width:70px" onclick="optioncli(1)"/>
		<input type="button" value="Modificar" style="width:70px" onclick="optioncli(2)"/>
		<?php if($page==cliente) echo '<input type="button" value="Eliminar" style="width:70px" onclick="optioncli(3)"/>'; ?>	
	</div>
	
	<div id="b6" style="float:left; width:310px; margin-top:5px; display:none">			
		<input type="button" value="Guardar"  style="width:100px" onclick="xajax_changeCli(xajax.getFormValues('FormCliente',true), modo)"/>
		<input type="button" value="Cancelar" style="width:100px" onclick="optioncli(4);"/>	
	</div>