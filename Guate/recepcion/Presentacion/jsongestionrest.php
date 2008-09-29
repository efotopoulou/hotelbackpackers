<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_familia.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_platillos.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/class_mesas.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/recepcion/Dominio/MensajeJSON.php');

$recarga =  $_POST['recarga'];
$familydeleteid =  $_POST['familydeleteid'];
$platodeleteid = $_POST['platodeleteid'];
$namefamily = $_POST['namefamily'];
$colorfam = $_POST['color'];
$namefamilymod = $_POST['namefamilymod'];
$famidmod = $_POST['famidmod'];
$idplato = $_POST['idplato'];
$nameplato = $_POST['nameplato'];
$precioN = $_POST['precioN'];
$precioL = $_POST['precioL'];
$cocina = $_POST['cocina'];
$platoFamily = $_POST['platoFamily'];
$idplatomod = $_POST['idplatomod'];
$precioNmod = $_POST['precioNmod'];
$precioLmod = $_POST['precioLmod'];
$numeroDeMesas = $_POST['numeroDeMesas'];

$familia = new class_familia();
$platillosinfo = new platillos();
$mensaje = new MensajeJSON();

try{
if ($familydeleteid){
$iddelete = substr($familydeleteid, 1);
$isFamilyFree=$platillosinfo->is_family_free($iddelete);
    if ($isFamilyFree) {
     $mensaje->setMensaje("No se puede eliminar esa familia porque contiene platillos!Si quiere eliminarla hay que eliminar primero los platillos que contiene.");	
    }else $familia->delete_family($iddelete);
$response = loadfamily($familia);
}else if ($platodeleteid){ 
$iddelete = substr($platodeleteid, 1);
$platillosinfo->delete_platillo($iddelete);	
$response = loadplatillos($platillosinfo);
}else if ($namefamily){
$familia->insert_family($namefamily,$colorfam);
$response = loadfamily($familia);
}else if($namefamilymod){
$idfam = substr($famidmod, 1);
$familia->modificar_family($namefamilymod,$idfam);
$response = loadfamily($familia);	
}else if ($idplato){
$platillosinfo->insert_platillo($idplato,$nameplato,$precioL,$precioN,$cocina,$platoFamily);
$response = loadplatillos($platillosinfo);
}else if ($idplatomod){
$idplato = substr($idplatomod, 1);
$platillosinfo->modificar_platillo($precioLmod,$precioNmod,$cocina,$idplato);
$response = loadplatillos($platillosinfo);	
}else if ($recarga) $response = load($familia,$platillosinfo);
else if ($numeroDeMesas){
$mesas = new mesas();
$mesas->modificar_mesas($numeroDeMesas);
}
}catch (SQLException $e){
	$aux = $e ->toString();
      if (stripos($e ->getNativeError(),"uplicate") != 0){
         $mensaje->setMensaje("Un platillo con el mismo ID ja existe en la Base de Datos!Por favor elige otro ID para el platillo que quiere anadir!");
      }else $mensaje->setMensaje("Error Desconocido: $aux!");
 }
$mensaje->setDatos($response);
echo($mensaje->encode());
?>

<?php
function loadfamily($familia){
$familia->get_familias();
$idfamilia=$familia->get_idfamily();
$nombrefamilia=$familia->get_family();
$colorfamily=$familia->get_colorfamily();
    for($i=0;$i<count($idfamilia);$i++) {
	   $FamiliasInfo[$i]=array("idfamilia"=>$idfamilia[$i],"nombre"=>$nombrefamilia[$i],"color"=>$colorfamily[$i]);
     }	
     $response["FamiliasInfo"]=$FamiliasInfo;
     return($response);
}
function loadplatillos($platillosinfo){
$platillosinfo->get_info_platos();
$platoid=$platillosinfo->get_plaidinfo();
$nombre=$platillosinfo->get_plainfo();
$precioNormal=$platillosinfo->get_plaPrecioNormalinfo();
$precioLimitado=$platillosinfo->get_plaPrecioLimitadoinfo();
$cocina=$platillosinfo->get_cocina();
$platoFamily=$platillosinfo->get_plfamily();
$platocolorFamily=$platillosinfo->get_plcolorfamily();
    for($i=0;$i<count($platoid);$i++) {
	     $PlatillosInfo[$i]=array("platoid"=>$platoid[$i],"nombre"=>$nombre[$i],"precioNormal"=>$precioNormal[$i],"precioLimitado"=>$precioLimitado[$i],"cocina"=>$cocina[$i],"familia"=>$platoFamily[$i],"color"=>$platocolorFamily[$i]);
    }
$response["PlatillosInfo"]=$PlatillosInfo;
return($response);
}
function load($familia,$platillosinfo){
$response = loadfamily($familia, null);
$response += loadplatillos($platillosinfo);
return($response);
}
?>
