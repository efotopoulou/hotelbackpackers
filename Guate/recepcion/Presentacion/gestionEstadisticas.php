<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_estadisticas.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$tipoEstadistica =  $_GET['tipoEstadistica'];
$year =  $_GET['year'];
$month =  $_GET['month'];
$week =  $_GET['week'];
$limit = $_GET['limit'];


$estadisticas = new estadisticas();
$mensaje = new MensajeJSON();

try{
if($tipoEstadistica=='CM' && $year && $month && $week){
$output = cajaWeek($year,$month,$week,$estadisticas);
$mensaje->setDatos($output);
}else if($tipoEstadistica=='CM' && $year && $month && !$week){
$output=cajaMonth($year,$month,$estadisticas);
$mensaje->setDatos($output);
}else if($tipoEstadistica=='CM' && $year && !$month && !$week){
$output=cajaYear($year,$estadisticas);
$mensaje->setDatos($output);
}else if($tipoEstadistica=='P' && $year && $month && !$week && $limit) {
$output = topPlatillosMonth($year,$month,$limit,$estadisticas);
$mensaje->setDatos($output);	
}else if($tipoEstadistica=='P' && $year && $month && $week && $limit){
$output = topPlatillosWeek($year,$month,$week,$limit,$estadisticas);
$mensaje->setDatos($output);	
}else if($tipoEstadistica=='P' && $year && !$month && !$week && $limit){
$output = topPlatillosYear($year,$limit,$estadisticas);
$mensaje->setDatos($output);	
}
}catch (SQLException $e){
	$aux = $e ->getNativeError();
    $mensaje->setMensaje("Error Desconocido: $aux!");
 }
 echo($mensaje->encode());
?>

<?php
function cajaYear($currentyear,$estadisticas){
  $strCaja="";
  $strmovE="";
  $strmovS="";
	
	$lineasCMY=$estadisticas->caja_movimientos_year($currentyear);
	//echo($lineasCMY[3]);
$strXML1 = "<graph caption='Total dinero ganado por mes durante el ".$currentyear."' xAxisName='dia' yAxisName='dinero' pieSliceDepth='30' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='Q' decimalPrecision='0'>";
$strXML2 = "<graph caption='Total movimientos por mes durante el ".$currentyear."' xAxisName='dia' yAxisName='dinero' pieSliceDepth='30' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='Q' decimalPrecision='0'>";
$strCategories = "<categories>";
$strmovE = "<dataset seriesName='Entradas' color='F5B462'>";
$strmovS = "<dataset seriesName='Salidas' color='9CD97A'>";

if ((sizeof($lineasCMY))>0){
		for($i=1;$i<=12;$i++){   
		
		$strCaja .= "<set name='" . monthsDescription($i) . "' value='" .$lineasCMY[$i]->suma . "' color='".colorMonth($i)."' />";
		$strmovE .= "<set value='" .$lineasCMY[$i]->entradas . "'/>";
		$strmovS .= "<set value='" .$lineasCMY[$i]->salidas . "'/>";
		$strCategories .= "<category name='" .monthsDescription($i). "' />";  
		}
	    $strmovE .= "</dataset>";
        $strmovS .= "</dataset>";
        $strCategories .= "</categories>";
        
        $strXMLcaja .= $strXML1 . $strCaja ."</graph>";
        $strXMLmovimientos .= $strXML2 . $strCategories . $strmovE . $strmovS . "</graph>";
}else{//si no hay informacion para este mes
	$strXMLcaja .= "<graph></graph>"; 
    $strXMLmovimientos .= "<graph></graph>";
	}
     //construir el xml de la cajas de este mes
	$result["strxml1"]=$strXMLcaja;
	$result["file1"]="/common/js/FusionCharts/FCF_Column3D.swf";
	$result["width1"]="1100";
	$result["height1"]="200";
	//construir el xml de los movimientos de este mes
	$result["strxml2"]=$strXMLmovimientos;
	$result["file2"]="/common/js/FusionCharts/FCF_MSColumn3D.swf";
	$result["width2"]="1100";
	$result["height2"]="200";
	return($result);	
}
function cajaMonth($currentyear,$currentmes,$estadisticas){
	$strCaja="";
	$strmovE="";
	$strmovS="";
	
	$lineasCMM=$estadisticas->caja_month($currentyear,$currentmes);
    $minas = monthsDescription($currentmes);
$strXML1 = "<graph caption='Total dinero ganado por dia durante el ".$minas." de ".$currentyear."' subCaption='".$minas." de ".$currentyear."'xAxisName='dia' yAxisName='dinero' pieSliceDepth='30' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='Q' decimalPrecision='0'>";
$strXML2 = "<graph caption='Total movimientos durante el ".$minas." de ".$currentyear."' subCaption='".$minas." de ".$currentyear."'xAxisName='dia' yAxisName='dinero' pieSliceDepth='30' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='Q' decimalPrecision='0'>";
$strCategories = "<categories>";
$strmovE = "<dataset seriesName='Entradas' color='F5B462'>";
$strmovS = "<dataset seriesName='Salidas' color='9CD97A'>";

if ((sizeof($lineasCMM))>0){
		$days = daysOfMonths($currentmes,$currentyear);
		for($i=1;$i<=$days;$i++){   
		$date = $currentyear."-".$currentmes."-".$i;
		//echo (gettype($lineasCMM[$i]->suma));
		$strCaja .= "<set name='" . $i . "' value='" .$lineasCMM[$i]->suma . "' color='".colorMonth($i)."' link='".urlencode("http://localhost:8081/recepcion/view.php?page=historicocaja&date=".$date). "' />";
		$strmovE .= "<set value='" .$lineasCMM[$i]->entradas . "'/>";
		$strmovS .= "<set value='" .$lineasCMM[$i]->salidas . "'/>";
		$strCategories .= "<category name='" .$i. "' />";  
		}
	    $strmovE .= "</dataset>";
        $strmovS .= "</dataset>";
        $strCategories .= "</categories>";
        
        $strXMLcaja .= $strXML1 . $strCaja ."</graph>";
        $strXMLmovimientos .= $strXML2 . $strCategories . $strmovE . $strmovS . "</graph>";
}else{//si no hay informacion para este mes
	$strXMLcaja .= "<graph></graph>"; 
    $strXMLmovimientos .= "<graph></graph>";
	}
     //construir el xml de la cajas de este mes
	$result["strxml1"]=$strXMLcaja;
	$result["file1"]="/common/js/FusionCharts/FCF_Column3D.swf";
	$result["width1"]="1100";
	$result["height1"]="200";
	//construir el xml de los movimientos de este mes
	$result["strxml2"]=$strXMLmovimientos;
	$result["file2"]="/common/js/FusionCharts/FCF_MSColumn3D.swf";
	$result["width2"]="1100";
	$result["height2"]="200";
	return($result);
}
function cajaWeek($year,$month,$week,$estadisticas){
	$date = $year."-".$month."-".$week;
	if (($week+6)>daysOfMonths($month,$year)) $minas = monthsDescription($month+1);
	else $minas = monthsDescription($month);

  $lineasCMW = $estadisticas->caja_movimientos_week($year,$date);
 if ((sizeof($lineasCMW))>0){
  $max=0;
    foreach($lineasCMW as $key1) {
     if ($max<count($key1)) $max=count($key1);
    }
    $strCategories = "<categories>";
    for ($i=1;$i<=$max;$i++){
     $strCaja[$i] = "<dataset seriesName='Caja".$i."'>";
     $strmovE[$i] = "<dataset seriesName='Entradas".$i."'>";
     $strmovS[$i] = "<dataset seriesName='Salidas".$i."'>";
    }
	reset($lineasCMW);
	$firstLevel = current($lineasCMW);
	$secondLevel = current($firstLevel);
	$firstdayofWeek =  ($secondLevel->fecha - $secondLevel->numday);
	$weekdays = volverWeek($firstdayofWeek,$month,$year);		
	$strXML1 = "<graph caption='Total dinero ganado en la ultima semana de ".$weekdays[0]."-".$weekdays[6]." de ".$minas." de ".$year."' subCaption=' ".$weekdays[0]."-".$weekdays[6]." de ".$minas." de ".$year."' xAxisName='dia' yAxisName='dinero' pieSliceDepth='30' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='Q' decimalPrecision='0'>";
	$strXML2 = "<graph caption='Total movimientos en la ultima semana de ".$weekdays[0]."-".$weekdays[6]." de ".$minas." de ".$year."' subCaption=' ".$weekdays[0]."-".$weekdays[6]." de ".$minas." de ".$year."' xAxisName='dia' yAxisName='dinero' pieSliceDepth='30' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='Q' decimalPrecision='0'>";
	
		for($i=0;$i<sizeof($weekdays);$i++){
			$linea =$lineasCMW[$weekdays[$i]];
			$n=1;
		    if ($linea){
			 foreach($linea as $key1) {
                //if ($key1->suma=="0") {$strCaja[$n] .= "<set value='" .$key1->suma . "' color='".colorweek($n)."'/>"; }else 
		        $strCaja[$n] .= "<set value='" .$key1->suma . "' color='".colorweek($n)."' link='".urlencode("http://localhost:8081/recepcion/view.php?page=historicocaja&idcaja=".$key1->id_caja). "'/>";
                $strmovE[$n] .= "<set value='" .$key1->entradas . "' color='".colorweek($n)."'/>";
                $strmovS[$n] .= "<set value='" .$key1->salidas . "' color='".colorweek($n)."'/>";
                $n++;
                }
		   }
           for ($j=$n;$j<=$max;$j++){
             $strCaja[$j] .= "<set value='0' />";
             $strmovE[$j] .= "<set value='0' />";
             $strmovS[$j] .= "<set value='0' />";	
            }
              $strCategories .= "<category name='" .nameOfWeek($i)." " .$weekdays[$i] . "' />";  
	   }
	   $strCategories .= "</categories>";
	   for ($i=1;$i<=$max;$i++){
        $strCaja[$i].= "</dataset>";
        $strmovE[$i].= "</dataset>";
        $strmovS[$i].= "</dataset>";
       }
    //construir el xml de la cajas de esta semana
	$strXMLcaja .= $strXML1 . $strCategories;
	foreach ($strCaja as $caja) $strXMLcaja .= $caja;  
	$strXMLcaja .= "</graph>";
	
	
	//construir el xml de los movimientos de esta semana
	$strXMLmovimientos .= $strXML2 . $strCategories;
	for ($i=1;$i<=$max;$i++){
    $strXMLmovimientos .= $strmovE[$i]; 
	$strXMLmovimientos .= $strmovS[$i];  
	}
	$strXMLmovimientos .= "</graph>";
	
 }else{
 	$strXMLcaja .= "<graph></graph>"; //si no hay informacion para esta semana
    $strXMLmovimientos .= "<graph></graph>";
 }
	//mantar el xml de la cajas de esta semana
	$result["strxml1"]=$strXMLcaja;
	$result["file1"]="/common/js/FusionCharts/FCF_MSColumn3D.swf";
	$result["width1"]="1100";
	$result["height1"]="200";
	//mantar el xml de los movimientos de esta semana
	$result["strxml2"]=$strXMLmovimientos;
	$result["file2"]="/common/js/FusionCharts/FCF_MSColumn3D.swf";
	$result["width2"]="1100";
	$result["height2"]="200";
	return($result);
}
function topPlatillosWeek($year,$month,$week,$limit,$estadisticas){
$date = $year."-".$month."-".$week;
	if (($week+6)>daysOfMonths($month,$year)) $minas = monthsDescription($month+1);
	else $minas = monthsDescription($month);

  $lineasTP = $estadisticas->topPlatillosWeek($year,$month,$date,$limit);
 if ((sizeof($lineasTP))>0){
 $strXML = "<graph caption='Top ".$limit." platillos durante la semana elegida de ".$minas."' showNames='1' decimalPrecision='0'>";
  for ($i=1;$i<=$limit;$i++){
  $strXML .= "<set name='" . $lineasTP[$i]->nombre . "' value='" . $lineasTP[$i]->freq ."'color='".colorMonth($i)."'/>";	
  }
  $strXML .= "</graph>";
 }else $strXML = "<graph></graph>"; //si no hay informacion para esta semana

	//mantar el xml de la cajas de esta semana
	$result["strxml"]=$strXML;
	$result["file"]="/common/js/FusionCharts/FCF_Pie3D.swf";
	$result["width"]="800";
	$result["height"]="600";
	return($result);	
}
function topPlatillosMonth($year,$month,$limit,$estadisticas){
$minas = monthsDescription($month);

  $lineasTP = $estadisticas->topPlatillosMonth($year,$month,$limit);
 if ((sizeof($lineasTP))>0){
 $strXML = "<graph caption='Top ".$limit." platillos durante el ".$minas."' showNames='1' decimalPrecision='0'>";
  for ($i=1;$i<=$limit;$i++){
  $strXML .= "<set name='" . $lineasTP[$i]->nombre . "' value='" . $lineasTP[$i]->freq ."'color='".colorMonth($i)."'/>";	
  }
  $strXML .= "</graph>";
 }else $strXML = "<graph></graph>"; //si no hay informacion para esta semana

	//mantar el xml de la cajas de esta semana
	$result["strxml"]=$strXML;
	$result["file"]="/common/js/FusionCharts/FCF_Pie3D.swf";
	$result["width"]="800";
	$result["height"]="600";
	return($result);	
}
function topPlatillosYear($year,$limit,$estadisticas){
  $lineasTP = $estadisticas->topPlatillosYear($year,$limit);
 if ((sizeof($lineasTP))>0){
 $strXML = "<graph caption='Top ".$limit." platillos durante el ".$year."' showNames='1' decimalPrecision='0' canvasBorderThickness='0'>";
  for ($i=1;$i<=$limit;$i++){
  $strXML .= "<set name='" . $lineasTP[$i]->nombre . "' value='" . $lineasTP[$i]->freq ."'color='".colorMonth($i)."'/>";	
  }
  $strXML .= "</graph>";
 }else $strXML = "<graph></graph>"; //si no hay informacion para esta semana

	//mantar el xml de la cajas de esta semana
	$result["strxml"]=$strXML;
	$result["file"]="/common/js/FusionCharts/FCF_Pie3D.swf";
	$result["width"]="800";
	$result["height"]="600";
	return($result);
}
function nameOfWeek($num){
$names=array("Lunes","Martes","Miercoles","Jueves", "Viernes","Sabado", "Domingo");
 return $names[$num];
}	
function monthsDescription($numMonth){
$month=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
 return $month[$numMonth-1];
}
function colorweek($i){
if ($i%2==0) return "F5B462";
else return "9CD97A";
}
function colorMonth($i){
$col=array('AFD8F8','F6BD0F','8BBA00','FF8E46','008E8E','D64646','8E468E','588526','B3AA00','008ED6','9D080D','A186BE');
	$aux=($i%12);
 return $col[$aux];
}	
function volverWeek($day,$month,$year){
	$daysofmonth = daysOfMonths($month-1,$year);	
	if(($day)<=0){
		for($i=0;$i<7;$i++){
			$week[$i]=$daysofmonth+$day;
			//$week[6-$i]=$daysofmonth+$day;
			$day++;
			if ($day==1)$daysofmonth = 0; 
			}
			return $week;
	}else if(($day+6)<=daysofmonth){
		$daysofmonth = daysOfMonths($month,$year);
		for($i=0;$i<7;$i++){
			$week[$i]=$day;
			$day++;
			}
			return $week;
	}else{
		for($i=0;$i<7;$i++){
			$week[$i]=$day;
			if ($day==$daysofmonth) $day=0;
			$day++;
			}
			return $week;
		}
	
	}
function daysOfMonths($numMonth,$year){
switch($numMonth){
case "1":				
$days='31';
				break;
case "2":				
if ($year%4==0) $days='29';
else $days='28';
				break;
case "3":				
$days='31';
				break;
case "4":				
$days='30';
				break;
case "5":				
$days='31';
				break;
case "6":				
$days='30';
				break;
case "7":				
$days='31';
				break;
case "8":				
$days='31';
				break;
case "9":				
$days='30';
				break;
case "10":				
$days='31';
				break;
case "11":				
$days='30';
				break;
case "12":				
$days='31';
				break;
			}
 return $days;
}
?>