<?php
 require_once ('Comunication.php');

$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction 
$clientType = $_GET['q'];//diferenciar entre la cuenta de un cliente y los trabajadores
if(!$sidx) $sidx =1; // connect to the database 
if(isset($_GET["nm_mask"])) 
 $nm_mask = $_GET['nm_mask']; 
else $nm_mask = ""; 
//construct where clause 
$where = ""; 

if($nm_mask!='') {
  $where.= " AND u.nombre LIKE '$nm_mask%'";
  $whereCount.= " WHERE nombre LIKE '$nm_mask%'";	
}

$comunication = new Comunication();

//$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error()); 
//mysql_select_db($database) or die("Error conecting to db.");
if ($clientType=="cliente"){
$rs = $comunication->query("SELECT COUNT(*) AS count FROM cliente ".$whereCount,array(),array()); 
}else{
$rs = $comunication->query("SELECT COUNT(*) AS count FROM recepcion_bd.trabajador ".$whereCount,array(),array()); 
}
//$rs = mysql_query("SELECT COUNT(*) AS count FROM invheader a, clients b WHERE a.client_id=b.client_id");
$rs->next();
$row=$rs->getRow(); 
//$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['count']; 
if( $count >0 ) {
	 $total_pages = ceil($count/$limit);
} else { 
 	$total_pages = 0; 
} 
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
//$SQL = "SELECT a.id, a.invdate, b.name, a.amount,a.tax,a.total,a.note FROM invheader a, clients b WHERE a.client_id=b.client_id ORDER BY $sidx $sord LIMIT $start , $limit";
if ($clientType=="cliente"){
$SQL = "SELECT t1.Id_cliente,t1.nombre,t1.apellido1,t1.apellido2,t1.pasaporte FROM cliente t1,checkin t2,reserva t3 WHERE t2.Id_res=t3.Id_res and t1.Id_cliente=t3.Id_cliente and date(NOW())>=t3.fec_ini and date(NOW())<=t3.fec_fin ".$where." ORDER BY ".$sidx." ".$sord." LIMIT ? , ?";
}else{
$SQL = "SELECT idTrabajador, nombre FROM recepcion_bd.trabajador ".$whereCount." ORDER BY ".$sidx." ".$sord." LIMIT ? , ?";
}

$result = $comunication->query($SQL,array($start,$limit),array(Comunication::$TINT,Comunication::$TINT)); 

//$result = mysql_query( $SQL ) or die("Couldnt execute query.".mysql_error());

if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
  	 header("Content-type: application/xhtml+xml;charset=utf-8");
} else { header("Content-type: text/xml;charset=utf-8"); 
} 
$et = ">"; 
echo "<?xml version='1.0' encoding='utf-8'?$et\n"; 
echo "<rows>"; 
echo "<page>".$page."</page>"; 
echo "<total>".$total_pages."</total>"; 
echo "<records>".$count."</records>"; // be sure to put text data in CDATA 
//while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
if ($clientType=="cliente"){
	while($result->next()){   
    $row=$result->getRow();
    echo "<row id='". $row["Id_cliente"]."'>";
    echo "<cell>". $row["Id_cliente"]."</cell>";
    echo "<cell>". $row["nombre"]."</cell>";
    echo "<cell>". $row["apellido1"]."</cell>";
    echo "<cell>". $row["apellido2"]."</cell>";
    echo "<cell>". $row["pasaporte"]."</cell>";
    echo "</row>";
} 
}
else{
while($result->next()){	
    $row=$result->getRow();
	echo "<row id='". $row["idTrabajador"]."'>"; 
	echo "<cell>". $row["idTrabajador"]."</cell>";
	echo "<cell>". $row["nombre"]."</cell>"; 
	echo "</row>"; 
} 
}
echo "</rows>";
?>
