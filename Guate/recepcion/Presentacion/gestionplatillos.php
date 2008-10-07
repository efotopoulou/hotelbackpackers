<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_mesas.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="MSSmartTagsPreventParsing" content="true">
<style>
tr{background:#FFF;text-align:right}
table{background:#DDD}
.btnpress{background:#E8C086}
.saved{background:#B9FAC4}
.redtext{color:red}
.changedisplay{display:none}
.precioN,.precioL,.platofam,.name{font-weight:bold;font-size:11px}
</style>
<script src="/common/js/jquery-1.2.3.pack.js" type="text/javascript"></script>
<script src="/common/js/ifx.js" type="text/javascript"></script>
<script src="/common/js/idrop.js" type="text/javascript"></script>
<script src="/common/js/idrag.js" type="text/javascript"></script>
<script src="/common/js/iutil.js" type="text/javascript"></script>
<script src="/common/js/islider.js" type="text/javascript"></script>
<link href="/common/js/color_picker/color_picker.css" rel="stylesheet" type="text/css">

<script src="/common/js/color_picker/color_picker.js" type="text/javascript"></script>
<script src="/common/js/guate.js"></script>
	<script type="text/javascript">
//------------------------------------------------------------AL CARGAR LA PAGINA--------------------------------------------------------------//	
$(document).ready(function(){
<?php
$mesas=new mesas();
$nummesas=$mesas->get_mesas();
?>
$("#nummesas").html(<?php echo($nummesas);?>);
recargaEstadoRestaurante();
});


</script>
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
</head>
<script>
function recargaEstadoRestaurante(){
 $("#numeroDeMesas").val("");
 $.getJSONGuate("Presentacion/jsongestionrest.php",{recarga:"yes"}, function(json){
  json = verificaJSON(json);
  loadpage(json);
 });
}
//-------------------------------------------LOADPAGE-------------------------------------------------//
function loadpage(json){
  loadfamilias(json);
  loadplatillos(json);
  loadmensages(json);	
}
function loadfamilias(json){
  if (json){
	if (json.FamiliasInfo){
  	  $("#familiasTable").html(" ");
  	  $("#platoFamily").html(" ");
      for(i=0;i<json.FamiliasInfo.length;i++) {
        $("#familiasTable").append("<tr id=F"+json.FamiliasInfo[i].idfamilia+" onmousedown='changeClass(this.id)'><td width=5%>&nbsp;</td><td width=30% class='color' bgcolor="+json.FamiliasInfo[i].color+"><h6>"+json.FamiliasInfo[i].idfamilia+"</h6></td><td width=65% class='name'>"+json.FamiliasInfo[i].nombre+"</td></tr>");
        $("#platoFamily").append("<option  style='width: 100%' value="+json.FamiliasInfo[i].idfamilia+">"+json.FamiliasInfo[i].nombre+"</option>");
       // $("#selectColor").append("<li style='width: 100%; background-color:"+json.FamiliasInfo[i].color+"'>&nbsp;</li>");
        
        }
    }else $("#familiasTable").html(" ");
 } 
}
function loadplatillos(json){
  if (json){
	if (json.PlatillosInfo){
  	  var aux="";
  	  $("#platillosTable").html(" ");
      for(i=0;i<json.PlatillosInfo.length;i++) {
        if (json.PlatillosInfo[i].cocina==1) {aux="si";} else {aux="no";}
        $("#platillosTable").append("<tr id=P"+json.PlatillosInfo[i].platoid+" onmousedown='changeClass(this.id)'><td width=2%>&nbsp;</td><td width=10%><h6>"+json.PlatillosInfo[i].platoid+"</h6></td><td width=35% class='name'>"+json.PlatillosInfo[i].nombre+"</td><td width=10% class='precioN'>"+json.PlatillosInfo[i].precioNormal+"</td><td width=10% class='precioL'>"+json.PlatillosInfo[i].precioLimitado+"</td><td width=10% class='cocina'><h6>"+aux+"</h6></td><td class='platofam' bgcolor="+json.PlatillosInfo[i].color+">"+json.PlatillosInfo[i].familia+"</td></tr>");		
        }
    }else $("#platillosTable").html(" ");
  }
}
function loadmensages(json){
if(json.mensages){
        alert(json.mensages);   
    }	
}
//-------------------------------------------CHANGECLASSID-------------------------------------------------//
function changeClass(id){
$(".saved").toggleClass("saved");
$(".btnpress").toggleClass("btnpress");
$(".redtext").toggleClass("redtext");
$("#"+id).toggleClass("btnpress");
$("#"+id).toggleClass("redtext");
}
//-------------------------------------------ELIMINAR-------------------------------------------------//
function eliminar(algo){
var a=algo;
	var familiaid=$("#familiasTable .btnpress").attr("id");
	var platilloid=$("#platillosTable .btnpress").attr("id");
	if (a=="family" && familiaid){
           if(confirm('�Estas seguro que quieres eliminar esta familia?')){
             $.getJSONGuate("Presentacion/jsongestionrest.php",{familydeleteid:familiaid}, function(json){json = verificaJSON(json);loadfamilias(json);loadmensages(json);});
           }else{$("#"+familiaid).toggleClass("btnpress");
		 	   $("#"+familiaid).toggleClass("redtext");}
		 	     
	}else if (a=="platillo" && platilloid){
		   if(confirm('�Estas seguro que quieres eliminar este platillo?')){
		     $.getJSONGuate("Presentacion/jsongestionrest.php",{platodeleteid:platilloid}, function(json){json = verificaJSON(json);loadplatillos(json);loadmensages(json);});
		   }else{$("#"+platilloid).toggleClass("btnpress");
		 	     $("#"+platilloid).toggleClass("redtext");}
	}
	if (a=="family" && !familiaid) alert("Por favor elige la familia que desea eliminar!");
	if (a=="platillo" && !platilloid) alert("Por favor elige el platillo que desea eliminar!");
}
//-------------------------------------------MODIFICAR-----------------------------------------------//
function modificar(algo){
	var a=algo;
	
	var familiaid=$("#familiasTable .btnpress").attr("id");
	var colormod=$("#familiasTable .btnpress .color").attr("bgcolor");
	var famname=$("#familiasTable .btnpress .name").html();
	
	var platilloid=$("#platillosTable .btnpress").attr("id");
	var nameplatomod=$("#platillosTable .btnpress .name").html();
	var precioNmod=$("#platillosTable .btnpress .precioN").html();
	var precioLmod=$("#platillosTable .btnpress .precioL").html();
	var cocina=1;
	if ($("#platillosTable .btnpress .cocina").html()=="<h6>no</h6>") cocina=0;
	//alert($("#platillosTable .btnpress .cocina").html());
	var platofam=$("#platillosTable .btnpress .platofam").html();
	
	if (a=="family" && familiaid){
	   $("#colormod").val(colormod);
	   $("#namefamilymod").val(famname);
	   changedisplay('b4mod');changedisplay('b3');changedisplay('modificarfamily');
	}else if (a=="platillo" && platilloid){
	   $("#idplatomod").val(platilloid);
	   $("#nameplatomod").val(nameplatomod);
	   $("#precioNmod").val(precioNmod);
	   $("#precioLmod").val(precioLmod);
	   $("#pantallaCocinamod").val(cocina);
	   $("#platofam").val(platofam);
	   $("#idplatomod,#nameplatomod,#platofam").attr({disabled:true});
	   changedisplay('b5');changedisplay('modificarplatillo');changedisplay('platomod');
	}
	if (a=="family" && !familiaid) alert("Por favor elige la familia que desea modificar!");
	if (a=="platillo" && !platilloid) alert("Por favor elige el platillo que desea modificar!");
}
//-------------------------------------------GUARDAR FAMILY------------------------------------------------//
//si los campos de color y nombre estan rellenados la pagina inserta la nueva familla a la bd sino pide del usuario que los inserte 
function guardarfamily(){
   var namefamily = $("#namefamily").val();
   var color = "#"+$("#myhexcode").val();
    if(namefamily && color ){
    	if(confirm('�Estas seguro que quieres guardar esta familia?')){
	      $.getJSONGuate("Presentacion/jsongestionrest.php",{namefamily:namefamily,color:color}, function(json){json = verificaJSON(json);loadfamilias(json);loadmensages(json);});
    	}
		$("#namefamily").val("");
		$("#myhexcode").val("");
		changedisplay('b4');
	    changedisplay('b3');changedisplay('editfamily');
    	
    }else alert("Por favor rellena correctamente los campos!");
}
//-------------------------------------------MODIFICAR FAMILY------------------------------------------------//
function modificarfamily(namefamilymod,colormod,familiaid){
   var namefamilymod = $("#namefamilymod").val();
   var familiaid=$("#familiasTable .btnpress").attr("id");
	if(namefamilymod && familiaid){
	     if(confirm('�Estas seguro que quieres modificar esta familia?')){
	        $.getJSONGuate("Presentacion/jsongestionrest.php",{namefamilymod:namefamilymod,famidmod:familiaid}, function(json){json = verificaJSON(json);loadfamilias(json);loadmensages(json);});
	     }
		 $("#namefamilymod").val("");
		 changedisplay('b4mod');
		 changedisplay('b3');changedisplay('modificarfamily');
	}else alert("Por favor rellena correctamente los campos!");
}
//-------------------------------------------GUARDAR PLATO------------------------------------------------//
function guardarplato(){
	$(".saved").toggleClass("saved");
    var idplato = $("#idplato").val();
	var nameplato = $("#nameplato").val();
	var precioN = parseFloat($("#precioN").val());
	var precioL = parseFloat($("#precioL").val());
	var cocina = parseInt($("#pantallaCocina").val());
	var platoFamily = $("#platoFamily").val();
	if(idplato && nameplato && precioN && precioL && cocina && platoFamily){
        if(confirm('�Estas seguro que quieres guardar este platillo?')){
           $.getJSONGuate("Presentacion/jsongestionrest.php",{idplato:idplato,nameplato:nameplato,precioN:precioN,precioL:precioL,cocina:cocina,platoFamily:platoFamily}, function(json){json = verificaJSON(json);loadplatillos(json);$("#P"+idplato).toggleClass("saved");});
        }
	    $("#idplato").val("");
		$("#nameplato").val("");
		$("#precioN").val("");
		$("#precioL").val("");
		$("#pantallaCocina").val("si");
		$("#platoFamily").val("");
		changedisplay('b5');
		changedisplay('editplatillo');
		changedisplay('b6');
	}else alert("Por favor rellena correctamente los campos!");
}
//-------------------------------------------MODIFICAR PLATO------------------------------------------------//
function modificarplato(){
    var idplatomod = $("#idplatomod").val();
	var precioNmod = $("#precioNmod").val();
	var precioLmod = $("#precioLmod").val();
	var cocina = $("#pantallaCocinamod").val();
	  if(idplatomod && precioNmod && precioLmod && cocina){
         if(confirm('�Estas seguro que quieres modificar este platillo?')){
             $.getJSONGuate("Presentacion/jsongestionrest.php",{idplatomod:idplatomod,precioNmod:precioNmod,precioLmod:precioLmod,cocina:cocina}, function(json){json = verificaJSON(json);loadplatillos(json);});
         }
		 $("#idplatomod").val("");
		 $("#nameplatomod").val("");
		 $("#precioNmod").val("");
		 $("#precioLmod").val("");
		 $("#pantallaCocinamod").val("");
		 $("#platofam").val("");
		 changedisplay('b5');
		 changedisplay('modificarplatillo');
		 changedisplay('platomod');		
	  }else alert("Por favor rellena correctamente los campos!");
	
}
//-----------------------------------------GUARDAR MESAS---------------------------------------------------------//
function guardarmesas(){
var numeroDeMesas = $("#numeroDeMesas").val();
if (numeroDeMesas){
     if(confirm('�Estas seguro que quieres modificar el numero de mesas del restaurante?')){
       $.getJSONGuate("Presentacion/jsongestionrest.php",{numeroDeMesas:numeroDeMesas},function(json){json = verificaJSON(json);});
       $("#nummesas").html(numeroDeMesas);
     }
     $("#numeroDeMesas").val("");
}else alert("Por favor selecciona el numero de mesas que quieres que tenga el restaurante!");
}
//------------------------------------------CHANGE DISPLAY--------------------------------------------------------//
function changedisplay(Seccion){ 
    $("#"+Seccion).toggleClass("changedisplay");
}
function vaciar(){
	$("#namefamily").val("");
	$("#myhexcode").val("");
}
</script>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>
<div id="principal" style="width:38%;">
	
	<h5 class="titulos">Gestion de mesas</h5>
	
  <div class="box_amarillo" style="margin-top:20px;margin-left:30px">
  <div><span class="label"><b><h3>Mesas:</h3></b></span>

			<div class="row" align="left">
			<div style="float:left;margin-top:10px"><span>El numero de mesas que hay en el restaurante es:</span></div>
			<div  id="nummesas" style="width:30px;float:left;margin-top:10px">0</div>
			
      		<div style="width:250px;float:left;margin-top:20px"><span>Introduzca el nuevo numero de mesas:</span></div>
      		<div style="width:30px;float:left;margin-top:20px">
      		  <SELECT id="numeroDeMesas" >
               <OPTION style="width:20px;"></OPTION>
 	           <OPTION>2</OPTION>
 	           <OPTION>4</OPTION>
 	           <OPTION>6</OPTION>
 	           <OPTION>8</OPTION>
 	           <OPTION>10</OPTION>
 	           <OPTION>12</OPTION>
 	           <OPTION>14</OPTION>
 	           <OPTION>16</OPTION>
             </SELECT>
           </div>
   		   </div>
           <div style="clear:both"></div>
           
           <div id="mesasmod" style="float:left; margin-top:5px;">			
			   <input type="button" value="Guardar" style="width:100px" onClick="guardarmesas();"/>
           </div>
		    <div style="clear:both"></div>
           
   </div>
   </div>
   <br>
   <br>
   
   
  <h5 class="titulos">Gestion de familias</h5>
   <div class="box_amarillo" style="height:45%;margin-top:20px;margin-left:30px">
    
	<div><span class="label"><b><h3>Familias:</h3></b></span>
        <table  width=97% cellpadding=0 cellspacing=1>
        <tr><td width=5%>&nbsp;</td><td width=30%><h6>Id_familia</h6></td><td width=65%><h6><center>Nombre</center></h6></td></tr>
        </table>
         <div style="overflow:auto">
         <table id="familiasTable" width=97% cellpadding=0 cellspacing=1>
         </table>
         </div>
   
        <div id="editfamily" style="float:left; margin-top:5spx" class="changedisplay">			
		<table>
		<tr><td bgcolor="#ecf8cb"><h6>Color de Familia:</h6></td>
		<td bgcolor="#ecf8cb">
		<FORM name="fcp">
			<div style="float:left;width:65px;display:block"><input type="text" id="myhexcode" value="" style="width:60px;"></div><div style="float:left">
			<a href="javascript:void(0);" rel="colorpicker&objcode=myhexcode&objshow=myshowcolor&showrgb=1" style="text-decoration:none;"><div id="myshowcolor" style="width:15px;height:15px;border:1px solid black;">&nbsp;</div></a></div>

		<script language="Javascript">
		 //init colorpicker:
		$(document).ready(function(){$.ColorPicker.init();});
		</script>
	    </FORM> 
		</td></tr>
		<tr><td bgcolor="#ecf8cb"><h6>Nombre de Familia:</h6></td>
		<td><input id="namefamily" value="" type="text"/></td></tr>
		</table>
		</div>
		
        
		<div id="modificarfamily" style="float:left; margin-top:5px" class="changedisplay">			
		<table>
		<tr><td bgcolor="#ecf8cb"><h6>Nombre de Familia:</h6></td>
		<td><input id="namefamilymod" value="" type="text"/></td></tr>
		</table>
		</div>
		
            <div id="b3" style="float:left; width:100%; margin-top:5px" >			
			   <input type="button" value="Anadir"  style="width:100px" onClick="changedisplay('b4');changedisplay('b3');changedisplay('editfamily');"/>
			   <input type="button" value="Modificar" id="family" style="width:100px" onClick="modificar(this.id)" />
			   <input type="button" value="Eliminar" id="family" style="width:100px" onClick="eliminar(this.id)"/>		
            </div>
            
            <div id="b4" style="float:left; margin-top:5px;" class="changedisplay">			
			   <input type="button" value="Guardar" style="width:100px" onClick="guardarfamily();"/>
               <input type="button" value="Cancelar" style="width:100px" onClick="changedisplay('b4');changedisplay('b3');changedisplay('editfamily');vaciar();"/>	
		    </div>
		    
		    <div id="b4mod" style="float:left; margin-top:5px;" class="changedisplay">			
			   <input type="button" value="Guardar" style="width:100px" onClick="modificarfamily()"/>
               <input type="button" value="Cancelar" style="width:100px" onClick="changedisplay('b4mod');changedisplay('b3');changedisplay('modificarfamily');"/>	
		    </div>
		
		</div>
   </div>
<br/>	
</div>

<div id="secundario" style="width:61%;">
	<h5 class="titulos">Gestion de platillos</h5>
	<div class="box_amarillo" style="width:90%;height:80%;margin-top:20px;margin-left:30px">
    
	<div><span class="label"><b><h3>Platillos:</h3></b></span>
      <table  width=95% cellpadding=0 cellspacing=1>
      <tr><td width=2%>&nbsp;</td><td width=10%><h6>idPlatillo</h6></td><td width=34%><h6><center>Nombre</center></h6></td><td width=10%><h6>precioN</h6></td><td width=10%><h6>precio(-)</h6></td><td width=10%><h6>cocina</h6></td><td><h6>familia</h6></td></tr>
      </table>
     <div style="height:80%;overflow:auto">
        <table id="platillosTable" width=97% cellpadding=0 cellspacing=1>
        </table>
     </div>
        <div id="editplatillo" style="margin-top:5px" class="changedisplay">			
		<table width=97%>
		<tr><td width=5% bgcolor="#ecf8cb"><img src="/common/img/flecha_dcha.jpg"></td>
		<td width=10%><input style="width: 100%" id="idplato" value="" type="text"/></td>
		<td width=30%><input style="width: 100%" id="nameplato" value="" type="text"/></td>
		<td width=10%><input style="width: 100%" id="precioN" value="" type="text"/></td>
		<td width=10%><input style="width: 100%" id="precioL" value="" type="text"/></td>
		<td width=10%><select id="pantallaCocina" style="width: 100%">
		<option value="1">si</option><option value="0">no</option>
		</select></td>
		<td width=25%><select id="platoFamily" style="width: 100%"></select></td>
		</tr>
		</table>
		</div>
		
		<div id="modificarplatillo" style="margin-top:5px" class="changedisplay">			
		<table width=97%>
		<tr><td width=5% bgcolor="#ecf8cb"><img src="/common/img/flecha_dcha.jpg"></td>
		<td width=10%><input style="width: 100%" id="idplatomod" value="" type="text"/></td>
		<td width=30%><input style="width: 100%" id="nameplatomod" value="" type="text"/></td>
		<td width=10%><input style="width: 100%" id="precioNmod" value="" type="text"/></td>
		<td width=10%><input style="width: 100%" id="precioLmod" value="" type="text"/></td>
		<td width=10%><select id="pantallaCocinamod" style="width: 100%">
		<option value="1">si</option><option value="0">no</option>
		</select></td>
		<td width=25%><input style="width: 100%" id="platofam" value="" type="text"/></td>
		</tr>
		</table>
		</div>
    
            <div id="b5" style="float:left; width:100%; margin-top:5px">			
			  <input type="button" value="Anadir"  style="width:100px" onClick="changedisplay('b5');changedisplay('editplatillo');changedisplay('b6');"/>
			  <input type="button" value="Modificar precio" id="platillo" style="width:100px" onClick="modificar(this.id)"/>
			  <input type="button" value="Eliminar" id="platillo" style="width:100px"  onClick="eliminar(this.id)"/>		
            </div>
            <div id="b6" style="float:left; margin-top:5px;" class="changedisplay">			
			   <input type="button" value="Guardar" style="width:100px" onClick="guardarplato();"/>
               <input type="button" value="Cancelar" style="width:100px" onClick="changedisplay('b5');changedisplay('editplatillo');changedisplay('b6');"/>	
		    </div>
		    <div id="platomod" style="float:left; margin-top:5px;" class="changedisplay">			
			   <input type="button" value="Guardar" style="width:100px" onClick="modificarplato();"/>
               <input type="button" value="Cancelar" style="width:100px" onClick="changedisplay('b5');changedisplay('modificarplatillo');changedisplay('platomod');"/>	
		    </div>
	
    </div>
    
   </div>
	
</div>

<!--				<div>
		<input type="text" id="myhexcode" value="" style="width:60px;">
        <a href="javascript:void(0);" rel="colorpicker&objcode=myhexcode&objshow=myshowcolor&showrgb=1&okfunc=myokfunc" style="text-decoration:none" ><div id="myshowcolor" style="width:15px;height:15px;border:1px solid black">&nbsp;</div></a>
		</div>-->
<br/>
</body>
</html>


