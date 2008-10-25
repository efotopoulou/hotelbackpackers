<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/restbar/Dominio/class_estadisticas.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title><!-- Meta Information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="MSSmartTagsPreventParsing" content="true">
<style>
tr{background:#FFF;text-align:right}
table{background:#66CC90}
.btnunpress{background:#e0edfe}
.redtext{color:red}
.verde{color:#4AD411}
.tableColor{background-color:#9CD97A;}
.highlight{ background:#F5B462; }
.changedisplay{display:none}
body {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
</style>
<link href="/common/css/estilo.css" rel="stylesheet" type="text/css" />
    <script src="/common/js/jquery-1.2.3.pack.js"></script>
    <script LANGUAGE="Javascript" SRC="/common/js/FusionCharts/FusionCharts.js"></script>
	<script src="/common/js/guate.js"></script>
<script type="text/javascript">
//------------------------------------------------------------AL CARGAR LA PAGINA--------------------------------------------------------------//	
$(document).ready(function(){
<?php
$anyos=new estadisticas();
$year=$anyos->yearsCaja();
for($i=0;$i<count($year);$i++) {
?>
	$("#years").append("<option value='<?php echo($year[$i]); ?>'><?php echo($year[$i]); ?></option>");

<?php } 
$hoy = getdate();
$year = $hoy['year'];
$month = $hoy['mon'];
?>
$("#years").val("<?php echo($year); ?>");
$("#month").val("<?php echo($month); ?>");
$("#numTopPlatillos").val("5");
$("#numTopBebidas").val("5");
currenyear = $("#years").val();
currentmonth = $("#month").val();
 showWeeks(); 
 loadDefaultGraph(currenyear,currentmonth);    
});

function changecolorCMP(id){
   var param="";
   $(".CMP").removeClass("highlight").addClass("tableColor");
   $("#"+id).addClass("highlight").removeClass("tableColor");
   param ="tipoEstadistica="+id; 
   if($("#A").hasClass("highlight"))  param+="&year="+$("#years").val();
   if($("#M").hasClass("highlight"))  param+="&month="+$("#month").val();
   if($("#S").hasClass("highlight"))  param+="&week="+$("#selectWeeks").val();
   if (id=='P' || id=='B' ){
   	  param+="&limit="+$("#numTopPlatillos").val();
   	  $.getJSONGuate("Presentacion/gestionEstadisticas.php?"+param,function(json){
 	  json = verificaJSON(json);
 	  //muestra el grapho de los platillos
 	  chart1 = new FusionCharts(json["file"]+"?ChartNoDataText=No hay informacion para el periodo de tiempo que te interesa!", "chart1Id",json["width"],json["height"], "0", "0");
      chart1.setDataXML(json["strxml"]);
	  chart1.render("chart1div");
	  $("#chart2div").html("");
 	});
   }else{
      $.getJSONGuate("Presentacion/gestionEstadisticas.php?"+param,function(json){
 	  json = verificaJSON(json);
 	  //muestra el grapho de la caja
 	  chart1 = new FusionCharts(json["file1"]+"?ChartNoDataText=No hay informacion para el periodo de tiempo que te interesa!", "chart1Id",json["width1"],json["height1"], "0", "0");
      chart1.setDataXML(json["strxml1"]);
	  chart1.render("chart1div");
	  //muestra el grapho de llos movimientos
	  chart2 = new FusionCharts(json["file2"]+"?ChartNoDataText=No hay informacion para el periodo de tiempo que te interesa!", "chart1Id",json["width2"],json["height2"], "0", "0");
      chart2.setDataXML(json["strxml2"]);
	  chart2.render("chart2div");
 	});
   }

}
function changecolorAMS(id){
	if (id=='A'){
	$("#A").addClass("highlight").removeClass("tableColor");
	$("#M").removeClass("highlight").addClass("tableColor");	
	$("#S").removeClass("highlight").addClass("tableColor");
	}else if (id=='M'){
	$("#A").addClass("highlight").removeClass("tableColor");
	$("#M").addClass("highlight").removeClass("tableColor");	
	$("#S").removeClass("highlight").addClass("tableColor");
	}else  if (id=='S'){
	$("#A").addClass("highlight").removeClass("tableColor");
	$("#M").addClass("highlight").removeClass("tableColor");	
	$("#S").addClass("highlight").removeClass("tableColor");	
	}
	
}
function showWeeks(){
var year = $("#years").val();
var month = $("#month").val();
mifecha = new Date(year, month-1, 1);
var result="";
var lastDayMonth=daysInMonth(month,year);
var firstDayWeek=1;
var lastDayWeek=1+((7-mifecha.getDay())%7);
var i=1;
while(lastDayWeek<lastDayMonth){
	result+="<option value='"+firstDayWeek+"'>Semana "+i+"="+firstDayWeek+"-"+lastDayWeek+"</option>";
	firstDayWeek=lastDayWeek+1;
	lastDayWeek+=7;
	i++;
}
result+="<option value='"+firstDayWeek+"'>Semana "+(i++)+"="+firstDayWeek+"-"+lastDayMonth+"</option>";
$("#selectWeeks").html(result);
}
function daysInMonth(month,year) {
var dd = new Date(year, month, 0);
return dd.getDate();
} 
function loadDefaultGraph(currenyear,currentmonth){
var chart;
 $.getJSONGuate("Presentacion/gestionEstadisticas.php?tipoEstadistica=CM&year="+currenyear+"&month="+currentmonth, function(json){
 	json = verificaJSON(json);
 	chart1 = new FusionCharts(json["file1"]+"?ChartNoDataText=No hay informacion para este mes!", "chart1Id",json["width1"],json["height1"], "0", "0");
    chart1.setDataXML(json["strxml1"]);
	chart1.render("chart1div");
	
	chart2 = new FusionCharts(json["file2"]+"?ChartNoDataText=No hay informacion para el periodo de tiempo que te interesa!", "chart1Id",json["width2"],json["height2"], "0", "0");
    chart2.setDataXML(json["strxml2"]);
	chart2.render("chart2div");
 	});
}
</script>
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menuRestBar.php'); ?>
<div id="gestionEstadisticas">
 
  <div class="box_amarillo" style="width:100%;height:10%">
    <table  width=100% height=100% cellpadding=1 cellspacing=1 border=1>
     <tr><td width=10% class="tableColor"><h6><center>BackPapers Estadisticas</center></h6></td>
     <td width=30% class="highlight AMS" id="A"  onclick="changecolorAMS(this.id);"><h6><center>Anyos</center></h6>
     <center><select id="years"  onchange='showWeeks();'></select></center>
     </td>
     <td width=30% class="highlight AMS" id="M"  onclick="changecolorAMS(this.id);"><h6><center>Meses</center></h6>
     <center>
     <select id='month' onchange='showWeeks();'>
     <option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option>	
	 <option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option>
	 </select></center>
     </td>
     <td  class="tableColor AMS" id="S"  onclick="changecolorAMS(this.id);"><h6><center>Semanas</center></h6>
     <center><select id="selectWeeks"></select></center>
     </td></td></tr>
    </table>
  </div>
 
  <div class="box_amarillo" style="width:10%;height:85%;float:left">
     <table id="CMPTable" width=100% height=100% cellpadding=1 cellspacing=1 border=1>
      <tr><td height=30% class="highlight CMP" id="CM" onclick="changecolorCMP(this.id);"><h6><center>Caja y movimientos</center></h6></td></tr>
      <tr><td width=50% height=30% class="tableColor CMP" id="P" onclick="changecolorCMP(this.id);"><h6><center>Top Platillos</center></h6>
      <br><center>
      <SELECT id="numTopPlatillos" >
               <OPTION style="width:20px;"></OPTION>
 	           <OPTION>5</OPTION>
 	           <OPTION>10</OPTION>
 	           <OPTION>15</OPTION>
 	           <OPTION>20</OPTION>
 	           <OPTION>30</OPTION>
 	           <OPTION>40</OPTION>
 	           <OPTION>50</OPTION>
             </SELECT></center>
      </td></tr>
      <tr><td width=50% class="tableColor CMP" id="B" onclick="changecolorCMP(this.id);"><h6><center>Top Bebidas</center></h6>
      <br><center>
      <SELECT id="numTopBebidas" >
               <OPTION style="width:20px;"></OPTION>
 	           <OPTION>5</OPTION>
 	           <OPTION>10</OPTION>
 	           <OPTION>15</OPTION>
 	           <OPTION>20</OPTION>
 	           <OPTION>30</OPTION>
 	           <OPTION>40</OPTION>
 	           <OPTION>50</OPTION>
             </SELECT></center>
      </td></tr>
     </table>
  </div>
  
  <div  style="width:100%;height:85%" id=ShowTable" class="box_amarillo">
     	<div  style="width:100%;height:50%" id="chart1div">
			FusionCharts
		</div>
		<div  style="width:100%;height:50%" id="chart2div">
			FusionCharts
		</div>
  </div>
 
</div>  <!--end of gestionEstadisticas-->

</body>
</html>


