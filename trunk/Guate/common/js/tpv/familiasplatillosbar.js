function getFamilias(){
 $.getJSON("../Presentacion/jsonplattpv.php", function(json){
    json = verificaJSON(json);
    crearFamilias(json);
 });
}
//function getFamiliasBar(file){
// $.getJSON(file, function(json){
 //   json = verificaJSON(json);
 //   crearFamilias(json);
// });
//}
function getPlatillosVentaRecepcion(file){
 $.getJSONGuate(file, function(json){
    json = verificaJSON(json);
    crearPlatVenta(json);
 });
}
function crearPlatVenta(json){
    var platillos =json["platillos"];
    var html ="<div class='platscroll'>"+
	          "<table style='text-align:center' width='100%' border=0 cellpadding='1' cellspacing='1'><tr  height='40'>";
	for(var i=0;i<platillos.length;i++){
    	 html +='<td><div class="plat" style="height:100%;" onmousedown="platomousedown(\''+platillos[i]["nombre"]+'\',\''+platillos[i]["idBebida"]+'\','+platillos[i]["precioNormal"]+','+platillos[i]["precioLimitado"]+',this.id)"><table width="100%" height="100%" style="text-align:center;background:#eac995;"><tr><td class="letrasPlatos">'+platillos[i]["nombre"]+'</td></tr></table></div></div></td>';
 		if (((i%3)==2) && (i+1!=platillos.length)) html +="</tr><tr height='40'>";
	}
	html +="</tr></table>"+
	'</div>';
	$("#platillos").append(html);
	$(".plat").corner();
}

function crearFamilias(json){
 var familias = new Array();
 var colores = new Array();
 var i = 0;
    for(var k in json["color"]){
      familias[familias.length]=k;
      colores[colores.length]=json["color"][k];
      if (json["familias"][k])
        if (json["familias"][k].length<=6) crearPlatillosHTML(json["familias"][k],colores[colores.length-1],i);
        else crearPlatillosScroll(json["familias"][k], colores[colores.length-1], i);
      i++;
   }
    if(familias.length<=6)crearFamiliasHTML(familias, colores);
    else crearFamiliasScroll(familias, colores);
}
function crearFamiliasHTML(familias, colores){
	var html ="<table style='text-align:center' width='100%' height='100%' border=0 cellpadding='1' cellspacing='1'><tr height='50%'>";
	for(var i=0;i<familias.length;i++){
		if (i==Math.round(familias.length/2)) html +="</tr><tr height='50%'>";
		html+="<td><div class='fam' id='familia"+i+"' style='background:"+colores[i]+";width:100%;height:100%'><table width='100%' height='100%' style='text-align:center'><tr><td>"+familias[i]+"</td></tr></table></div></td>";
	}
	html +="</tr></table>";
	$("#familias").html(html);
	$(".fam").corner().addClass("link").click(function(){
		showPlatillo(this.id);
	});
}
function crearFamiliasScroll(familias, colores){
	var width = Math.round(($("#familias").width()-37)/3);
    var height = Math.round($("#familias").height()/2)-2;

	var html = '<div id="familiasscroll" style="height:100%">'+
	'<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr height="100%">'+
    '<td><div class="prev"></div></td>'+
    '<td><div class="items">';
    
    for(var i=0;i<familias.length;i++){
    	if ((i%2)==0){
    		 html += '<div class="item">'+
    		            '<div class="fam famScroll" id="familia'+i+'" style="margin-bottom:2px;height:'+height+'px;width:'+width+'px;background:'+colores[i]+'">'+
    		             '<table width="100%" height="100%" style="text-align:center"><tr><td>'+familias[i]+'</td></tr></table>'+
    		            '</div>';
    	}
    	else {
    	 html +='<div class="fam famScroll" id="familia'+i+'" style="height:'+height+'px;width:'+width+'px;background:'+colores[i]+'">'+
    	           '<table width="100%" height="100%" style="text-align:center"><tr><td>'+familias[i]+'</td></tr></table>'+
    	         '</div></div>';
    	}
    }
    if ((familias.length%2)!=0) html +="</div>";
    html +='</div></td>'+  
	'<td><div class="next"></div></td>'+
    '</tr></table>'+
    '</div>';
	$("#familias").html(html);
	$("#familiasscroll").scrollable({items:'.items',horizontal:true,size:3});
	$(".fam").corner().addClass("link").click(function(){
		showPlatillo(this.id);
	});
}
function showPlatillo(id){
	id = id.replace("familia","platillosscroll");
	$(".platscroll").css({display:"none"});
	$("#"+id).css({display:"block"});
}
function resize(){
//   alert ($(".fam").size());
    var famSize=$(".fam").size();
    famSize=Math.round(famSize/2);
	var widthFamilias = Math.round(($("#familias").width()-32)/famSize);
    var heightFamilias = Math.round($("#familias").height()/2)-2;
    var widthPlat = Math.round(($("#familias").width()-37)/3);
    var heightPlat = Math.round($("#familias").height()/2)-2;
  	$(".scrollPlat").width(widthPlat).height(heightPlat);
    $(".platscroll .table").attr({width:Math.round($("#platillosTd").width())}).attr({height:Math.round($("#platillosTd").height())});
    $(".items").width(Math.round($("#platillosTd").width())-30);
  	$(".famScroll").width(widthFamilias).height(heightFamilias);
}
function crearPlatillosScroll(platillos, color, numPlat){
  	var width = Math.round(($("#platillos").width()-37)/3);
	var height = Math.round($("#platillos").height()/2)-2;
    var html ='<div id="platillosscroll'+numPlat+'" class="platscroll" style="height:100%;position:absolute">'+
    '<table class="table" width="100%" height="100%" cellpadding="0" cellspacing="0"><tr height="100%">'+
    '<td><div class="prev"></div></td>'+
    '<td><div class="items">';

    for(var i=0;i<platillos.length;i++){
    	if ((i%2)==0) html += '<div class="item"><div class="plat scrollPlat" style="margin-bottom:2px;height:'+height+'px;width:'+width+'px;background-color:'+color+'" onmousedown="platomousedown(\''+platillos[i]["nombre"]+'\',\''+platillos[i]["idBebida"]+'\','+platillos[i]["precioNormal"]+','+platillos[i]["precioLimitado"]+',this.id)"><table width="100%" height="100%" style="text-align:center"><tr><td>'+platillos[i]["nombre"]+'</td></tr></table></div>';
	   	else html +='<div class="plat scrollPlat" onmousedown="platomousedown(\''+platillos[i]["nombre"]+'\',\''+platillos[i]["idBebida"]+'\','+platillos[i]["precioNormal"]+','+platillos[i]["precioLimitado"]+',this.id)" style="height:'+height+'px;width:'+width+'px;background:'+color+'"><table width="100%" height="100%" style="text-align:center"><tr><td>'+platillos[i]["nombre"]+'</td></tr></table></div></div>';
    }
    if ((platillos.length%2)!=0) html +="</div>"; 
    html += '</div></td>'+ 
	'<td><div class="next"></div></td>'+
    '</tr></table>'+
    '</div>';
    $("#platillos").append(html);
	$("#platillosscroll"+numPlat).scrollable({items:'.items',horizontal:true,size:3});
}
function crearPlatillosHTML(platillos,color, numPlat){
    var html ="<div id='platillosscroll"+numPlat+"' class='platscroll' style='height:100%;position:absolute;display:none'>"+
	          "<table style='text-align:center' width='100%' border=0 cellpadding='1' cellspacing='1'><tr  height='60'>";
	for(var i=0;i<platillos.length;i++){
    	 html +='<td><div class="plat" onmousedown="platomousedown(\''+platillos[i]["nombre"]+'\',\''+platillos[i]["idBebida"]+'\','+platillos[i]["precioNormal"]+','+platillos[i]["precioLimitado"]+',this.id)" style="height:100%;background:'+color+'"><table width="100%" height="100%" style="text-align:center"><tr><td>'+platillos[i]["nombre"]+'</td></tr></table></div></div></td>';
 		if ((i%3)==2) html +="</tr><tr height='60'>";
	}
	html +="</tr></table>"+
	'</div>';
	$("#platillos").append(html);
}
