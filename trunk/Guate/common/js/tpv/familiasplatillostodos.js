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

