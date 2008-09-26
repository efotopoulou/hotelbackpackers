<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_familiabebida.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_bebidas.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_stock.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$recarga =  $_GET['recarga'];
$familydeleteid =  $_GET['familydeleteid'];
$bebidadeleteid = $_GET['bebidadeleteid'];
$namefamily = $_GET['namefamily'];
$colorfam = $_GET['color'];
$namefamilymod = $_GET['namefamilymod'];
$famidmod = $_GET['famidmod'];
$idbebida = $_GET['idbebida'];
$namebebida = $_GET['namebebida'];
$precioN = $_GET['precioN'];
$precioL = $_GET['precioL'];
$bebidaFamily = $_GET['bebidaFamily'];
$idbebidamod = $_GET['idbebidamod'];
$precioNmod = $_GET['precioNmod'];
$precioLmod = $_GET['precioLmod'];

$familia = new class_familia();
$bebidasinfo = new bebidas();
$mensaje = new MensajeJSON();

try{
if ($familydeleteid){
$iddelete = substr($familydeleteid, 1);
$isFamilyFree=$bebidasinfo->is_family_free($iddelete);
    if ($isFamilyFree) {
     $mensaje->setMensaje("No se puede eliminar esa familia porque contiene platillos!Si quiere eliminarla hay que eliminar primero los platillos que contiene.");	
    }else $familia->delete_family($iddelete);
$response = loadfamily($familia);
}else if ($bebidadeleteid){ 
$iddelete = substr($bebidadeleteid, 1);
$stock = new stock();
$stocktotal = $stock->get_stock_bebida($iddelete);

$bebidasinfo->delete_bebida($iddelete);	
$response = loadbebidas($bebidasinfo);

$mensaje->setMensaje("El stock actual de la bebida que acabas de eliminar era ".$stocktotal." unidades.");	
   
}else if ($namefamily){
$familia->insert_family($namefamily,$colorfam);
$response = loadfamily($familia);
}else if($namefamilymod){
$idfam = substr($famidmod, 1);
$familia->modificar_family($namefamilymod,$idfam);
$response = loadfamily($familia);	
}else if ($idbebida){
$bebidasinfo->insert_bebida($idbebida,$namebebida,$precioL,$precioN,$bebidaFamily);
$response = loadbebidas($bebidasinfo);
}else if ($idbebidamod){
$idbebida = substr($idbebidamod, 1);
$bebidasinfo->modificar_bebida($precioLmod,$precioNmod,$idbebida);
$response = loadbebidas($bebidasinfo);	
}else if ($recarga) $response = load($familia,$bebidasinfo);
}catch (SQLException $e){
	$aux = $e ->getNativeError();
      if (stripos($e ->getNativeError(),"uplicate") != 0){
      $mensaje->setMensaje("Una bebida con el mismo ID ja existe en la Base de Datos!Por favor elige otro ID para la bebida que quiere anadir!");
      }else $mensaje->setMensaje("Error Desconocido: $aux!");
 }
$mensaje->setDatos($response);
echo($mensaje->encode());
?>

<?php
function loadfamily($familia){
$familias=$familia->get_families();
if ((sizeof($familias))>0){
	  for($i=0;$i<count($familias);$i++) {
	  $FamiliasInfo[$i]=array("idfamilia"=>$familias[$i]->id,"nombre"=>$familias[$i]->name,"color"=>$familias[$i]->color);
	  }
  $response["FamiliasInfo"]=$FamiliasInfo;
  return($response);
}
}
function loadbebidas($bebidasinfo){
$bebidas = $bebidasinfo->get_info_bebidas();
if ((sizeof($bebidas))>0){
	  for($i=0;$i<count($bebidas);$i++) {
	  $BebidasInfo[$i]=array("idBebida"=>$bebidas[$i]->idBebida,"numBebida"=>$bebidas[$i]->numBebida,"nombre"=>$bebidas[$i]->nombre,"precioLimitado"=>$bebidas[$i]->precioLimitado,"precioNormal"=>$bebidas[$i]->precioNormal,"color"=>$bebidas[$i]->color,"familia"=>$bebidas[$i]->familia);
	  }
  $response["BebidasInfo"]=$BebidasInfo;
  return($response);
}
}
function load($familia,$bebidasinfo){
$barbebidas=loadbebidas($bebidasinfo);
$response = loadfamily($familia);
if ($barbebidas) $response += $barbebidas;
return($response);
}
?>
