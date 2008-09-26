<?php 
/*$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$tiempoinicial = $mtime; */

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']))
     exit;

include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_eventos.php');
include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_calendario.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/hotel/Dominio/class_habitaciones.php');

include($_SERVER['DOCUMENT_ROOT'] . '/hotel/Presentacion/calendario.func.php');

include($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_caja.php');

if($_POST!=null){
	$d=$_POST['d'];
	$m=$_POST['m'];
	$y=$_POST['y'];
	$t=$_POST['t'];
}
else{
	$d=date("d");
	$m=date("m");
	$y=date("Y");
}
$center_date=$d."/".$m."/".$y;

$cal=new calendario($d, $m, $y);
$habit=new alojamiento();
$habit->get_all_aloj();
$evento=new eventos($cal->get_day_ini(), $cal->get_day_end());

require_once ($_SERVER['DOCUMENT_ROOT'] . '/hotel/xajax.req.php');
$xajax->printJavascript('xajax/'); 
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title></title><!-- Meta Information -->
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta name="MSSmartTagsPreventParsing" content="true">
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	   
	   <script src="/common/js/jquery-1.2.3.pack.js"></script>
	   <script src="/common/js/jquery.blockUI.js"></script>
	   
	   
		<link href="css/estilo.css" rel="stylesheet" type="text/css" />	
		<script src="scripts/nav.js"></script>	
	</head>
		
	<script src="/hotel/scripts/scroll.js"></script>
	
	<script type="text/javascript">
	    var GB_ROOT_DIR = "/hotel/scripts/greybox/";
	</script>
	
	<script type="text/javascript" src="scripts/greybox/AJS.js"></script>
	<script type="text/javascript" src="scripts/greybox/gb_scripts.js"></script>
	<link href="scripts/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
	
<script>
	var d=<?php print $d ?>;
	var m=<?php print $m ?>;
	var y=<?php print $y ?>;
	
	var xajax_lock=false;
	
	function changeEvData(idEv, data, opc){
		if(xajax_lock==false){
			xajax_lock=true;
			xajax_changeEvData(idEv, data, opc);
		}
	}
	
	function showBox(event, idEv, tipo){
		if (!event) var event = window.event;
		if(tipo>=0){	//si no es "modificar/cancelar reserva"
			hideBox();			
			box=document.getElementById("BoxDiv");
			// para NS e.pageX/Y, hay que pasar evento...
			box.style.top=event.clientY + 15;
			box.style.left=event.clientX - 35;
		}
		xajax_loadBox(idEv, tipo);
		
		//box.style.display="";
	}
	
	function hideBox(){
		document.getElementById("BoxDiv").style.display="none";
	}
	
	function showLiteras(idParent){	
		hideBox();

		if(document.getElementById("containerLeft_"+idParent).style.display == ""){			
			document.getElementById("containerLeft_"+idParent).style.display="none";
			document.getElementById("containerMid_"+idParent).style.display="none";
			document.getElementById("alojLeft_"+idParent).style.backgroundImage="url(/hotel/img/arrow_d.gif)";
		}
		else{
			document.getElementById("alojLeft_"+idParent).style.backgroundImage="url(/hotel/img/ajax-loader.gif)";
			xajax_loadLit(idParent, d, m, y);	
		}
	}


	var dini_idHabit=0;
	var dini_numDias=0;
	
	function rangeReserva(event){
		if (!event) var event = window.event;
		var element = event.target || event.srcElement;
		v=element.id.split("_");
		idHabit=v[1];
		numDias=parseInt(v[2]);
		if(event.type=="click"){
			hideBox();			
			if(dini_idHabit==idHabit){		//2º click en la misma fila
				if(numDias>dini_numDias)
					makeReserva(idHabit,dini_numDias,numDias);
				else
					makeReserva(idHabit,numDias,dini_numDias);
			}
			else if(dini_idHabit>0){		//1º click en una fila habiendo hecho un click en otra fila anteriormente
				document.getElementById("c_"+dini_idHabit+"_"+dini_numDias).className='t_cell_div';
			}
			dini_idHabit=idHabit;
			dini_numDias=numDias;
			document.getElementById("c_"+dini_idHabit+"_"+numDias).className+=' rango_res';
		}		
		else if(event.type=="mouseover"){			
			if(dini_idHabit==idHabit){		//mouseover sobre una fila en la que hay un click hecho
				document.getElementById("c_"+idHabit+"_"+numDias).className+=' rango_res';	
			}
		}
		else if(event.type=="mouseout"){
			if(dini_idHabit==idHabit && numDias!=dini_numDias){ 	//para no borrar el 1º click hecho
				document.getElementById("c_"+dini_idHabit+"_"+numDias).className='t_cell_div';				
			}
		}
	}
	
	function makeReserva(idHabit, numDiasIni, numDiasEnd){
		fecha_str=document.FormRes.fecha_start_calend.value;
		v=fecha_str.split("/");
		fecha=new Date();
		fecha.setFullYear(v[2],v[1]-1,v[0]);
		fecha.setDate(fecha.getDate()+numDiasIni);
					
		document.FormRes.id_habit.value=idHabit;
		
		document.FormRes.fecha_ini.value=LZ(fecha.getDate())+"/"+LZ(fecha.getMonth()+1)+"/"+fecha.getFullYear();
		fecha.setDate(fecha.getDate()+(numDiasEnd-numDiasIni));
		document.FormRes.fecha_fin.value=LZ(fecha.getDate())+"/"+LZ(fecha.getMonth()+1)+"/"+fecha.getFullYear();
		document.FormRes.submit();
	}
	function LZ(x) { return (x>=10||x<0?"":"0") + x }
	
	function makeCheck(tipo, idEv){
		if(tipo=="in")
			document.FormCheck.action="view.php?page=checkinres";
		else // "out"
			document.FormCheck.action="view.php?page=checkout";
		document.FormCheck.id_ev.value=idEv;
		document.FormCheck.submit();
	}
	
	function ir_a(){
		d=document.getElementById('go_to').value;
		fecha=d.split("/");
		document.FormNext.d.value=fecha[0];
		document.FormNext.m.value=fecha[1];
		document.FormNext.y.value=fecha[2];
		document.FormNext.t.value=0;
		document.FormNext.submit();	
	}
	
	
	function moveCalend(){
		document.getElementById('calend_mid').scrollLeft=(24*<?php echo calc_scrollLeft($t, $cal); ?>);
		document.getElementById('calend_top').scrollLeft=document.getElementById('calend_mid').scrollLeft;
		if(window.addEventListener){ // Mozilla, Netscape, Firefox		
			document.getElementById('calend_mid').addEventListener("scroll",hideBox,true);
		}
		else {
			document.getElementById('calend_mid').attachEvent("onscroll",hideBox);
		}
		ScrollIni();
	}
	
	function showColDayAct(idTable){
		numDias=<?php echo $cal->get_sum_till_act()?>;
		
		if(arguments[0].length>0)
			idTable=arguments[0];	// tableContainer_X
		else
			idTable='tableMid';
		
		for (var i=0;i<document.getElementById(idTable).rows.length;i++){				
			id=document.getElementById(idTable).rows[i].id;
			id=id.split('_');
			idHabit=id[1];
			if(document.getElementById('c_'+idHabit+'_'+numDias)){
				document.getElementById('c_'+idHabit+'_'+numDias).className+=' hoy';		
			}
		}
	}
	
	var today=<?php echo ($cal->get_day_act()==$cal->get_today()) ?>0;
	
	if(window.addEventListener){ // Mozilla, Netscape, Firefox
		window.addEventListener("load",moveCalend,true);
		if(today) window.addEventListener("load", showColDayAct,true);
	}
	else{		
		window.attachEvent("onload", moveCalend);
		if(today) window.attachEvent("onload", showColDayAct);  
	}
	function cajaCerrada(){
   $.blockUI({ message: $('#cajaCerrada')}); 	
 }
</script>
<body
 <?php
 $openOrClose=new caja();
 $estadocaja=$openOrClose->estado_caja();
 if ($estadocaja==0){
 ?>
//alert("hola");
onload="cajaCerrada();"
 <?php }?>
>



<div id="base">

<div id="cajaCerrada" style="display:none">la caja esta cerrada<br /><a href="/recepcion/view.php?page=caja">Abrir caja</a></div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/Presentacion/menu.php'); ?>
<div id="principal" style="width:100%">

<form action="view.php?page=reserva" method="POST" name="FormRes">
	<input type="hidden" name="modo" value="crear"/>
	<input type="hidden" name="id_habit" value=""/>		
	<input type="hidden" name="fecha_start_calend" value="<?php echo date("d/"."m/"."Y",$cal->get_day_ini()); ?>"/>
	<input type="hidden" name="fecha_ini" value=""/>
	<input type="hidden" name="fecha_fin" value=""/>
</form>	

<form action="" method="POST" name="FormCheck">
	<input type="hidden" name="modo" value="crear"/>
	<input type="hidden" name="id_ev" value=""/>		
</form>

<div style="float:left"><?php echo date("d/"."m/"."Y",$cal->get_day_ini()); ?></div>
<div style="float:right; padding-right:10px"><?php echo date("d/"."m/"."Y",$cal->get_day_end()); ?></div>
	<div id="calend">	
			<div id="calend_left">
					<?php genera_left($habit); ?>
		  	</div>

			<div id="calend_right">
				<div id="calend_top">
					<?php genera_top($cal); ?>
				</div>

				<div id="calend_mid">
					<?php genera_mid($habit, $evento, $cal); ?>				
				</div>
			</div>
			
			<div id="calend_bottom">
			<table class="t_general">
				<tr>
				  <td  bgcolor="#ecf8cb" width="100px">&nbsp;</td>
				  <td  bgcolor="#ecf8cb"  width="100%">
					<div>
					<ul style="margin:0px">
					<li style="display:inline; text-decoration:underline; width:43%; float:left">
						<form action="view.php?page=calendario" method="POST" name="FormBack">
							<?php genera_bottom_hiddens($cal, 1)?>
							<a href="javascript: document.FormBack.submit()">Atras</a>
						</form>
					</li>
					<li style="display:inline; text-decoration:underline;  float:left"><input id="go_to" name="go_to" type="text" value="<?php echo $center_date; ?>" size="9"/><input type="button" value="Ir a..." onClick="ir_a()"></li>
					<li style="display:inline; text-decoration:underline; float:right;">
						<form action="view.php?page=calendario" method="POST" name="FormNext">
							<?php genera_bottom_hiddens($cal, 2)?>
							<a href="javascript: document.FormNext.submit()">Siguiente</a>
						</form>
					</li>
					</ul>
					</div>
				</td>
				</tr>
			</table>
			</div>
			
	</div>


</div>
</div>

</div>

<div id="BoxDiv" style="display:none">
<img style="position: absolute; left: 33px; top: -16px;" src="/hotel/img/cal_box_border_arrow.gif" onClick="hideBox()">
</div>

</body>
</html>
<?php
/*$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$tiempofinal = $mtime;
$tiempototal = ($tiempofinal - $tiempoinicial);
echo "La pagina fue creada en ".$tiempototal." segundos";*/
?>