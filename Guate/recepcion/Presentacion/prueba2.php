<html lang="en" dir="ltr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Comida</title>
		<link rel="stylesheet" type="text/css" media="screen" href="/common/css/grid.css" />
		<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />


<script src="/common/jqgrid_demo/js/jquery.js" type="text/javascript"></script>
<script src="/common/js/jquery.blockUI.js"></script>
		<script src="/common/js/json.js"></script>
		<script src="/common/js/guate.js"></script>

<script>
$(document).ready(function(){
   $.blockUI({ message: '<h1>Cargando...</h1>' });
   getFamilias();
//   restoreHibernar();
   $.unblockUI();
});
function getFamilias(){
 $.getJSONGuate("/recepcion/Presentacion/jsonplattpv.php", function(json){
    json = verificaJSON(json);
    crearFamilias(json);
 },false);
}
function crearFamilias(json){
 var familias = new Array();
 var colores = new Array();
 //Numero de columnas de plato
 var numPlatCol = 9;
 var posPlatCol=0;
 html="<table style='text-align:center' width='100%' border=0 cellpadding='1' cellspacing='1'><tr>";
    for(var k in json["color"]){
      familias[familias.length]=k;
      colores[colores.length]=json["color"][k];
      if (json["familias"][k]){
      	platillos=json["familias"][k];
      	color=colores[colores.length-1];
        for(i=0;i<platillos.length;i++){
    	 html +='<td style="height:100%;background:'+color+'" onmousedown="platomousedown(\''+platillos[i]["nombre"]+'\',\''+platillos[i]["idBebida"]+'\','+platillos[i]["precioNormal"]+','+platillos[i]["precioLimitado"]+',this.id)"><table cellpadding="0" cellspacing="0" width="100%" height="100%" style="text-align:center"><tr><td class="plat2" >'+platillos[i]["nombre"]+'</td></tr></table></td>';
    	 posPlatCol++;
 		 if (posPlatCol%numPlatCol==0) html +="</tr><tr>";
        }
      }
   }
   	html+="</tr></table>";
   	$("#platillos").append(html);
}
function crearPlatillosHTML(platillos,color,numPlatCol,posPlatCol){
    var i;
    var html="";
	for(i=posPlatCol;i<platillos.length;i++){
    	 html +='<td><div class="plat2" onmousedown="platomousedown(\''+platillos[i]["nombre"]+'\',\''+platillos[i]["idBebida"]+'\','+platillos[i]["precioNormal"]+','+platillos[i]["precioLimitado"]+',this.id)" style="height:100%;background:'+color+'"><table width="100%" height="100%" style="text-align:center"><tr><td>'+platillos[i]["nombre"]+posPlatCol+'</td></tr></table></div></div></td>';
 		if ((i%numPlatCol)==0) html +="</tr><tr>";
	}
	$("#platillos").append(html);
	return (i%numPlatCol);
}

</script>

</head>
<body style="margin:0pt;padding:0pt">
 <div style="border:solid 1px black;margin:0pt;padding:0pt;height:94%">
  <table id="platos" height="100%" width="100%" cellspacing=0 cellpadding=2 border=1>
   <tr>
    <td id="platillosTd" height="50%" width="50%" rowspan=2>
       <div style="position: relative; width: 100%; height: 100%;" id="platillos"></div>
    </td>
    <td height="50%" width="50%">
     <div id="arriba_derecha" style="height:100%;width:100%">a</div>
    </td>
   </tr>
   <tr>
    <td height="50%" width="50%">
     <div id="abajo_derecha" style="height:100%;width:100%">a</div>
    </td>
   </tr>
  </table>
 </div>
</body>
</html>

