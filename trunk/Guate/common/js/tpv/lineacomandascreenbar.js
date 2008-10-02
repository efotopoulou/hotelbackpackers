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
//    if(main.comanda().numRow==0) this.addTotal("0");
//    if(main.comanda().numRow==0)$("#mytable"+main.currentComanda).after(precioOnComanda(main.currentComanda,"0"));
    $("#lista"+this.actualComanda).append(this.platilloHtml(platillo,"currentRow", id));
//        $("#arriba_derecha").window.scrollTo(0,10);
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
  	 var html ='<div id="total'+this.actualComanda+'"><div style="float:right;width:80px;height:1px;background:#000;margin-right:15px"></div>'+
  	           '<table style="text-align:center;clear:right" width=97% border=0 cellpadding=0 cellspacing=1>'+
  	           '<tr><td>&nbsp;</td><td width=20% id="precioTotal'+this.actualComanda+'" style="font-weight:bold">'+precio+
  	           '</td></tr></table></div>';
   $("#comanda"+this.actualComanda).after(html);
  }

  this.modifyTotal = function(precio){
  	 $("#precioTotal"+this.actualComanda).html(precio);
  }
  
  this.vaciar = function(){
   $("#arriba_derecha").html("");
   this.actualComanda = -1;
 }
 this.getCantidad = function(){
   return $("#row"+main.currentComanda+main.comanda().numRow +" .can").html();
 }
 this.setCantidad = function(newCan){
   $("#row"+main.currentComanda+main.comanda().numRow +" .can").html(newCan);
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
   $("#row"+main.currentComanda+main.comanda().numRow +" .precioN").html(newprecio);
 }
 this.removeLine = function(){
   $("#row"+main.currentComanda+main.comanda().numRow).remove();
 }
 this.fijarComanda = function(){
    $(".currentRow").addClass("fixedRow").removeClass("currentRow");
    $("#precioTotal"+main.currentComanda).addClass("precioFixed");
 }
 
}