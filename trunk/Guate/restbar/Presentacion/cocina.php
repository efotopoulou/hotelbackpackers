<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_mesas.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="MSSmartTagsPreventParsing" content="true">
<link href="/common/css/chat.css" rel="stylesheet" type="text/css" />

<style>
tr{background:#FFF;text-align:right}
table{background:#DDD}
.fonto{background:black}
.letras{color:#FFFFFF;font-family:Arial;font-size:25px;}
.white{background:#FFFFFF}
.saved{background:#B9FAC4}
.blacktext{color:black}
.changedisplay{display:none}
.precioN,.precioL,.platofam,.name{font-weight:bold;font-size:11px}
#pedidosTable{background:black;} 
</style>
<script src="/common/js/jquery-1.2.3.pack.js" type="text/javascript"></script>
<script src="/common/js/ifx.js" type="text/javascript"></script>
<script src="/common/js/idrop.js" type="text/javascript"></script>
<script src="/common/js/idrag.js" type="text/javascript"></script>
<script src="/common/js/iutil.js" type="text/javascript"></script>
<script src="/common/js/islider.js" type="text/javascript"></script>
<script src="/common/js/jquery.jclock.js" type="text/javascript"></script>
<script src="/common/js/periodicalUpdate.js" type="text/javascript"></script>
<link href="/common/js/color_picker/color_picker.css" rel="stylesheet" type="text/css">

<script src="/common/js/color_picker/color_picker.js" type="text/javascript"></script>
<script src="/common/js/guate.js"></script>
<script src="/common/js/testsound.js"></script>
	<script type="text/javascript">

//------------------------------------------------------------AL CARGAR LA PAGINA--------------------------------------------------------------//
var nick="cocina";
var lastchatid=0;
var refresh=5;

$(document).ready(function(){
 skata();
 $("#eliminar").mousedown(function(){
  var cantidad=$("#pedidosTable .white .cantidad").html();
  var nombre=$("#pedidosTable .white .nombre").html();
  var numComanda=$("#pedidosTable .white .numComanda").html();
  if (cantidad != null) $(".writeInput").find(':input').val('Listo '+cantidad+' '+nombre+' de '+numComanda).parent().trigger('submit');
 });
  read();
 $(".writeInput").submit(function(){
   write();
   return false;
 });
});
// handle the read messages function
function read(){
$.getJSONGuate("/restbar/Presentacion/serverchat.php",{serv:1,nick:nick, lastchatid:lastchatid} ,function(json){
	json = verificaJSON(json);
	if (json){
	 if (json.Mensajes){
	 	for(i=0;i<json.Mensajes.length;i++) {
	 	var msg = json.Mensajes[i];
	 	  $(".chat").append('<p><small>('+ msg.time +')</small> '+msg.nickname+' &gt; <strong>'+msg.msg+'</strong></p>');
	 	}
	 }
	 if (json.lastchatid) lastchatid=json.lastchatid;
	}
	var objDiv = document.getElementById("chatCocina");
	objDiv.scrollTop = objDiv.scrollHeight;
	//sound2Play();
	$("#lastchat").html(lastchatid);
	setTimeout(read,refresh*1000);
	});
}
function write(){
	var input = $(".writeInput").find(':input');
	var message = input.val();
	if ($.trim(message).length > 0) { // need to have something to say !
		input.val('');
		input.blur();
		input.attr("disabled", "disabled"); // we have to this so there'll be less spam messages
		$.getJSONGuate("/restbar/Presentacion/serverchat.php",{serv:2,nick:nick, msg: message} ,function(json){
			json = verificaJSON(json);
			input.removeAttr("disabled");
			input.focus();
			if (json && json.Mensajes){
				var data =  json.Mensajes;
				$(".chat").append('<p><small>('+ data.time +')</small> ' + data.nickname + ' &gt; <strong>' + data.msg + '</strong></p>');
			}
			var objDiv = document.getElementById("chatCocina");
    		objDiv.scrollTop = objDiv.scrollHeight;					
		});
	}
}

function skata(){
  $.getJSONGuate("Presentacion/jsongestioncocina.php", function(json){
  json = verificaJSON(json);
  loadpedidos(json);
  $.periodic(skata, {frequency: 10});
 });
}
</script>
	<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
</head>
<script>
var pedidoElegido=0;
//-------------------------------------------LOAD PEDIDOS-------------------------------------------------//
function loadpedidos(json){
  if (json){
	if (json.PedidosInfo){
  	  var aux="";
  	  var platos=$(".platos").size(); 
  	  $("#pedidosTable").html(" ");
      for(i=0;i<json.PedidosInfo.length;i++) {
        $("#pedidosTable").append("<tr class='platos' id='"+json.PedidosInfo[i].idCocina+"' onmousedown='changeClass(this.id);pedidoElegido=this.id;'><td width=2%>&nbsp;</td><td width=10%><h3 class='numComanda'>"+json.PedidosInfo[i].numComanda+"</h3></td><td width=15%><h3>"+json.PedidosInfo[i].idPlatillo+"</h3></td><td><center class='nombre'>"+json.PedidosInfo[i].nombre+"</center></td><td width=10%><center class='cantidad'>"+json.PedidosInfo[i].cantidad+"</center></td><td width=15%>"+json.PedidosInfo[i].hora+"</td></tr>");		
        if (json.PedidosInfo[i].idCocina==pedidoElegido) {btncolor(pedidoElegido);}
        $("#"+json.PedidosInfo[i].idCocina).addClass("fonto letras");
      }
      if (platos <json.PedidosInfo.length){
        	 sound2Play();
      }
      platos=json.PedidosInfo.length;
    }else $("#pedidosTable").html(" ");
  }
}

//-------------------------------------------ELIMINAR PEDIDO-------------------------------------------------//
function eliminarpedido(){
var pedidoID=$("#pedidosTable .white").attr("id");
$.getJSONGuate("Presentacion/jsongestioncocina.php",{eliminarpedido:pedidoID}, function(json){
  json = verificaJSON(json);
  loadpedidos(json);
 });	
}
//-------------------------------------------RECUPERAR PEDIDO-------------------------------------------------//
function recuperarpedido(){
$.getJSONGuate("Presentacion/jsongestioncocina.php",{recuperarpedido:"yes"}, function(json){
  json = verificaJSON(json);
  loadpedidos(json);
 });	
}
//-------------------------------------------CHANGECLASSID-------------------------------------------------//
function changeClass(id){
$(".white").toggleClass("white");
$(".blacktext").toggleClass("blacktext");
pedidoElegido=id;
btncolor(id);
}
function btncolor(id){

$("#"+id).toggleClass("white");
$("#"+id).toggleClass("blacktext");	

}
</script>
<script type="text/javascript">
    $(function($) {
      var options = {
        timeNotation: '24h',
        am_pm: true,
        fontFamily: 'Arial',
        fontSize: '30px',
        foreground: 'white',
        background: 'black'
      }
      $('.jclock').jclock(options);
    });
</script>
<body>

<div id="secundario" style="margin-top:0px;width:100%;height:100%;background:#000000;">
  
      <table  width=97% cellpadding=0 cellspacing=1  style="background:#000000;">
      <tr class="fonto letras"><td width=2%>&nbsp;</td><td width=10%><h3>Comanda</h3></td><td width=15%><h3>Platillo</h3></td><td><h3><center>Nombre</center></h3></td><td width=10%><h3>Cant.</h3></td><td width=15%><h3>Hora</h3></td></tr>
      </table>
     <div style="height:60%;overflow:auto">
        <table id="pedidosTable" width=97% cellpadding=0 cellspacing=1>
        </table>
        <div id="lastchat"></div>
     </div>
     <div id="b5" style=" width:100%;">	
     <table style="width:100%;background:#000000;">		
	  <tr class="fonto letras">
	  <td  id="recuperar" width=25% align="center" onmousedown="btncolor(this.id);recuperarpedido();" onmouseup="btncolor(this.id)">Recuperar pedido</td>
	  <!--  <input type="button" value="Recuperar pedido" style="width:100px" onClick="recuperarpedido()"/></td> -->
	  <td><div class="jclock"  align="center" style="width:400px;"></div></center></td>
	  <td  id="eliminar" width=25% align="center" onmousedown="btncolor(this.id);eliminarpedido();" onmouseup="btncolor(this.id)">Servir pedido</td>
	  </tr> 
	 </table>		
    </div>
<div id="myChat">
	<div class="chat" id="chatCocina">
		<div></div>
	</div>
	<div style="display:none">
	  <form action="" method="post" class="writeInput">
		<input type="text" value="" />
	  </form>
    </div>
</div>

<script type="text/javascript">
//$(function(){
//	$('#myChat').ajaxChat();
//});
</script>
 
</div>

<br/>
</body>
</html>



