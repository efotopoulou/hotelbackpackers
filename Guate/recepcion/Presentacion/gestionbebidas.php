
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="MSSmartTagsPreventParsing" content="true">
<style>
tr{background:#FFF;text-align:right}
table{background:#DDD}
.btnpress{background:blue}
.saved{background:#B9FAC4}
.redtext{color:red}
.changedisplay{display:none}
.precioN,.precioL,.platofam,.name,.codigo{font-weight:bold;font-size:11px}
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
recargaEstadoRestaurante();
});


</script>
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
</head>
<script>
function recargaEstadoRestaurante(){
 $.getJSON("Presentacion/jsongestionbar.php",{recarga:"yes"}, function(json){
  json = verificaJSON(json);
  loadpage(json);
 });
}
//-------------------------------------------LOADPAGE-------------------------------------------------//
function loadpage(json){
  loadfamilias(json);
  loadbebidas(json);
}
function loadfamilias(json){
  if (json){
	if (json.FamiliasInfo){
  	  $("#familiasTable").html(" ");
  	  $("#bebidaFamily").html(" ");
  	  $("#platoFamily").html(" ");
      for(i=0;i<json.FamiliasInfo.length;i++) {
        $("#familiasTable").append("<tr id=F"+json.FamiliasInfo[i].idfamilia+" onmousedown='changeClass(this.id)'><td width=5%>&nbsp;</td><td width=30% class='color' bgcolor="+json.FamiliasInfo[i].color+"><h6>"+json.FamiliasInfo[i].idfamilia+"</h6></td><td width=65% class='name'>"+json.FamiliasInfo[i].nombre+"</td></tr>");
        $("#bebidaFamily").append("<option  style='width: 100%' value="+json.FamiliasInfo[i].idfamilia+">"+json.FamiliasInfo[i].nombre+"</option>");
       // $("#selectColor").append("<li style='width: 100%; background-color:"+json.FamiliasInfo[i].color+"'>&nbsp;</li>");
        
        }
    }else $("#familiasTable").html(" ");
 }else $("#familiasTable").html(" "); 
}
function loadbebidas(json){
  if (json){
	if (json.BebidasInfo){
  	  var aux="";
  	  $("#bebidasTable").html(" ");
      for(i=0;i<json.BebidasInfo.length;i++) {
        $("#bebidasTable").append("<tr id=B"+json.BebidasInfo[i].idBebida+" class='Cod"+json.BebidasInfo[i].numBebida+"' onmousedown='changeClass(this.id)'><td width=2%>&nbsp;</td><td width=10% class='codigo'>"+json.BebidasInfo[i].numBebida+"</td><td width=35% class='name'>"+json.BebidasInfo[i].nombre+"</td><td width=10% class='precioN'>"+json.BebidasInfo[i].precioNormal+"</td><td width=10% class='precioL'>"+json.BebidasInfo[i].precioLimitado+"</td><td class='platofam' bgcolor="+json.BebidasInfo[i].color+">"+json.BebidasInfo[i].familia+"</td></tr>");		
        //if (json.BebidasInfo[i].numBebida==codigo)
        }
    }else $("#bebidasTable").html(" ");
  }else $("#bebidasTable").html(" ");
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
	var platilloid=$("#bebidasTable .btnpress").attr("id");
	if (a=="family" && familiaid){
           if(confirm('�Estas seguro que quieres eliminar esta familia?')){
             $.getJSON("Presentacion/jsongestionbar.php",{familydeleteid:familiaid}, function(json){json = verificaJSON(json);loadfamilias(json);});
           }else{$("#"+familiaid).toggleClass("btnpress");
		 	   $("#"+familiaid).toggleClass("redtext");}
		 	     
	}else if (a=="platillo" && platilloid){
		   if(confirm('�Estas seguro que quieres eliminar este platillo?')){
		     $.getJSON("Presentacion/jsongestionbar.php",{bebidadeleteid:platilloid}, function(json){json = verificaJSON(json);loadbebidas(json);});
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
	
	//var platilloid=$("#bebidasTable .btnpress").attr("id");
	var platilloid=$("#bebidasTable .btnpress .codigo").html();
	var nameplatomod=$("#bebidasTable .btnpress .name").html();
	var precioNmod=$("#bebidasTable .btnpress .precioN").html();
	var precioLmod=$("#bebidasTable .btnpress .precioL").html();
	//var cocina=1;
	//if ($("#bebidasTable .btnpress .cocina").html()=="<h6>no</h6>") cocina=0;
	//alert($("#bebidasTable .btnpress .cocina").html());
	var platofam=$("#bebidasTable .btnpress .platofam").html();
	
	if (a=="family" && familiaid){
	   $("#colormod").val(colormod);
	   $("#namefamilymod").val(famname);
	   changedisplay('b4mod');changedisplay('b3');changedisplay('modificarfamily');
	}else if (a=="platillo" && platilloid){
	   $("#idplatomod").val(platilloid);
	   $("#nameplatomod").val(nameplatomod);
	   $("#precioNmod").val(precioNmod);
	   $("#precioLmod").val(precioLmod);
	   //$("#pantallaCocinamod").val(cocina);
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
	      $.getJSON("Presentacion/jsongestionbar.php",{namefamily:namefamily,color:color}, function(json){json = verificaJSON(json);loadfamilias(json);});
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
	        $.getJSON("Presentacion/jsongestionbar.php",{namefamilymod:namefamilymod,famidmod:familiaid}, function(json){json = verificaJSON(json);loadfamilias(json);});
	     }
		 $("#namefamilymod").val("");
		 changedisplay('b4mod');
		 changedisplay('b3');changedisplay('modificarfamily');
	}else alert("Por favor rellena correctamente los campos!");
}
//-------------------------------------------GUARDAR BEBIDA------------------------------------------------//
function guardarbebida(){
	$(".saved").removeClass("saved");
    var idbebida = $("#idbebida").val();
	var namebebida = $("#namebebida").val();
	var precioN = parseFloat($("#precioN").val());
	var precioL = parseFloat($("#precioL").val());
	var bebidaFamily = $("#bebidaFamily").val();
	if(idbebida && namebebida && precioN && precioL && bebidaFamily){
        if(confirm('�Estas seguro que quieres guardar este platillo?')){
           $.getJSON("Presentacion/jsongestionbar.php",{idbebida:idbebida,namebebida:namebebida,precioN:precioN,precioL:precioL,bebidaFamily:bebidaFamily}, function(json){
           	  json = verificaJSON(json);
           	  loadbebidas(json,idbebida);
           	  $(".Cod"+idbebida).addClass("saved");
           	  alert("Recuerda a informar el control de stock sobre el stock de esta bebida!");
           });
        }
	    $("#idbebida").val("");
		$("#namebebida").val("");
		$("#precioN").val("");
		$("#precioL").val("");
		$("#bebidaFamily").val("");
		changedisplay('b5');
		changedisplay('editplatillo');
		changedisplay('b6');
	}else alert("Por favor rellena correctamente los campos!");
}
//-------------------------------------------MODIFICAR BEBIDA------------------------------------------------//
function modificarbebida(){
    var platilloid=$("#bebidasTable .btnpress").attr("id");
    //var idbebidamod = $("#idplatomod").val();
	var precioNmod = $("#precioNmod").val();
	var precioLmod = $("#precioLmod").val();
	  if(platilloid && precioNmod && precioLmod){
         if(confirm('�Estas seguro que quieres modificar esta bebida?')){
             $.getJSON("Presentacion/jsongestionbar.php",{idbebidamod:platilloid,precioNmod:precioNmod,precioLmod:precioLmod}, function(json){json = verificaJSON(json);loadbebidas(json);});
         }
		 $("#idplatomod").val("");
		 $("#nameplatomod").val("");
		 $("#precioNmod").val("");
		 $("#precioLmod").val("");
		 $("#platofam").val("");
		 changedisplay('b5');
		 changedisplay('modificarplatillo');
		 changedisplay('platomod');		
	  }else alert("Por favor rellena correctamente los campos!");
	
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
<div id="principal" style="width:37%;">
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

<div id="secundario" style="width:62%;">
	<h5 class="titulos">Gestion de bebidas</h5>
	<div class="box_amarillo" style="width:90%;height:80%;margin-top:20px;margin-left:30px">
    
	<div><span class="label"><b><h3>Bebidas:</h3></b></span>
      <table  width=95% cellpadding=0 cellspacing=1>
      <tr><td width=2%>&nbsp;</td><td width=10%><h6>idBebida</h6></td><td width=36%><h6><center>Nombre</center></h6></td><td width=10%><h6>precioN</h6></td><td width=10%><h6>precio(-)</h6></td><td><h6>familia</h6></td></tr>
      </table>
     <div style="height:80%;overflow:auto">
        <table id="bebidasTable" width=97% cellpadding=0 cellspacing=1>
        </table>
     </div>
        <div id="editplatillo" style="margin-top:5px" class="changedisplay">			
		<table width=97%>
		<tr><td width=5% bgcolor="#ecf8cb"><img src="/common/img/flecha_dcha.jpg"></td>
		<td width=10%><input style="width: 100%" id="idbebida" value="" type="text"/></td>
		<td width=30%><input style="width: 100%" id="namebebida" value="" type="text"/></td>
		<td width=10%><input style="width: 100%" id="precioN" value="" type="text"/></td>
		<td width=10%><input style="width: 100%" id="precioL" value="" type="text"/></td>
		<td width=25%><select id="bebidaFamily" style="width: 100%"></select></td>
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
			   <input type="button" value="Guardar" style="width:100px" onClick="guardarbebida();"/>
               <input type="button" value="Cancelar" style="width:100px" onClick="changedisplay('b5');changedisplay('editplatillo');changedisplay('b6');"/>	
		    </div>
		    <div id="platomod" style="float:left; margin-top:5px;" class="changedisplay">			
			   <input type="button" value="Guardar" style="width:100px" onClick="modificarbebida();"/>
               <input type="button" value="Cancelar" style="width:100px" onClick="changedisplay('b5');changedisplay('modificarplatillo');changedisplay('platomod');"/>	
		    </div>
	
    </div>
    
   </div>
	
</div>
<br/>
</body>
</html>


