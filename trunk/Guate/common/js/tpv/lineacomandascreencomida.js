// CLASES DE PRESENTACION
//Clase LineaComandaScreen
function LineaComandaScreen(){
  this.actualComanda = -1;

  this.iniciar = function(){
    $("#arriba_derecha").append(this.comandaScreen());
    //poner los titulos
    $("#lista"+this.actualComanda).append(this.titleScreen());
    this.addTotal("0");
  }
  this.reiniciar = function(){
    this.vaciar();
    this.iniciar();
  }
  this.comandaScreen = function (){
    this.actualComanda++;
    var html = '<div id="comanda'+this.actualComanda+'">'+
                  '<table id="lista'+this.actualComanda+'" class="center" width="100%" cellspacing="1" cellpadding="0" border="0"></table>'+
               '</div>';
    return html;
  }

  this.addComanda = function(){
    $("#arriba_derecha").append(this.comandaScreen());
    $("#lista"+this.actualComanda).append(this.titleScreen());
    this.addTotal("0");
  }
  this.titleScreen = function(){
    var html = '<tr class="title">'+
                 '<td width="3%"/>'+
                 '<td id="platoid" width="8%">Pl.ID</td>'+
                 '<td id="can" width="7%">Can.</td>'+
                 '<td id="producto">Producto</td>'+
                 '<td id="unitario" width="13%">Pr.Uni.</td>'+
                 '<td id="precio" width="20%">Precio</td>'+
               '</tr>';
    return html;
  }

  this.addPlatillo = function(platillo, id){
    $("#lista"+this.actualComanda).append(this.platilloHtml(platillo,"currentRow", id));
    var objDiv = document.getElementById("arriba_derecha");
    objDiv.scrollTop = objDiv.scrollHeight;
  }
  this.addPlatilloFijo = function(platillo){
    if(!platillo.cantidad) platillo.cantidad='1';
    $("#lista"+this.actualComanda).append(this.platilloHtml(platillo,"fixedRow"));
  }
  
  this.platilloHtml = function(platillo, class, id){
      var can = platillo.cantidad;
      if(!can) can='1';
      if (id) id="id='"+id+"'";
      else id="";
      return "<tr class='"+class+"' "+id+"><td width=3%>&nbsp;</td><td width=8% class=plaid>"+platillo.platoId+"</td><td width=7% class=can>"+can+"</td><td width=50% class=producto>"+platillo.producto+"</td><td width=13% class=precioU>"+platillo.precioUnidad+"</td><td width=20% class=precioN>"+platillo.precioN+"</td></tr>";
  }
  
  this.addTotal = function(precio){
  var propina = this.calcularPropina(precio);
  	 var html ='<div id="total'+this.actualComanda+'"><div style="float:right;width:80px;height:1px;background:#000;margin-right:15px"></div>'+
  	           '<table style="text-align:center;clear:right" width=97% border=0 cellpadding=0 cellspacing=1>'+
  	           '<tr><td id="mensajeCocina" rowspan=3>&nbsp;</td><td width=20%>SubTot.<span id="precioTotal'+this.actualComanda+'" style="font-weight:bold">'+precio+'</span>'+
  	           '</td></tr><tr><td width=20%>Prop.<span  id="propina'+this.actualComanda+'" style="font-weight:bold">'+propina+'</span></td></tr>'+
  	           '<tr><td width=20%>Tot.<span id="TotalPropina'+this.actualComanda+'" style="font-weight:bold">'+(parseInt(parseInt(precio)+parseInt(propina)))+'</span></td></tr></table></div>';
   $("#comanda"+this.actualComanda).after(html);
  }

  this.modifyTotal = function(precio){
     var propina = this.calcularPropina(precio);
     var totalpropina = parseInt(parseInt(precio)+parseInt(propina));
  	 $("#precioTotal"+this.actualComanda).html(""+precio);
  	 $("#propina"+this.actualComanda).html(""+propina);
  	 $("#TotalPropina"+this.actualComanda).html(""+totalpropina);
  	 $("#total").val(totalpropina);
  	 return totalpropina;
  }
  
  this.vaciar = function(){
   $("#arriba_derecha").html("");
   this.actualComanda = -1;
 }
 this.getCantidad = function(){
   return $("#row"+main.currentCom()+main.comanda().numRow +" .can").html();
 }
 this.setCantidad = function(newCan){
   $("#row"+main.currentCom()+main.comanda().numRow +" .can").html(newCan);
 }
 this.calcCantidad = function(newCan){
  if(main.linia().cantidad){
   listaPedidos.setCantidad(listaPedidos.getCantidad()+new String(newCan));
  }else{
   listaPedidos.setCantidad(newCan);
   main.linia().cantidad ="";
  }
 }
 this.setPrecio = function(newprecio){
   if (!newprecio) newprecio="0";
   $("#row"+main.currentCom()+main.comanda().numRow +" .precioN").html(newprecio);
 }
 this.removeLine = function(){
   $("#row"+main.currentCom()+main.comanda().numRow).remove();
 }
 this.fijarComanda = function(){
    $(".currentRow").addClass("fixedRow").removeClass("currentRow");
    $("#precioTotal"+main.currentCom()).addClass("precioFixed");
    $("#propina"+main.currentCom()).addClass("precioFixed");
    $("#TotalPropina"+main.currentCom()).addClass("precioFixed");
 }
 this.calcularPropina = function(precio){
 var propina =0;
  if(main.comandaAbierta()&& main.comanda().currentClientType!=2){
   propina = parseInt(precio/10);
  }    
  return propina;
 }
 this.mensajeCocina=function(men){
  $("#total"+main.currentCom()+" #mensajeCocina").html(men);
 }
}