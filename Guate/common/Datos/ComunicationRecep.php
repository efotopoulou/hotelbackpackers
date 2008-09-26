<?php
/**
 *	Descripci�n:
 *		Esta clase permite al usuario realizar peticiones de qualquier tipo (SELECT, INSERT,
 *		UPDATE, DELETE, ALTER, CREATE, ...) a la base de datos mediante la funci�n com_query.
 *		En funci�n de la sentencia ejecutada devolver� un resultado coherente definido en la
 *		especificaci�n de la propia funci�n.
 *
 *	Ejemplos de utilizaci�n al final del fichero.
 */
//require_once ('../Dominio/class_log.php');
require_once ($_SERVER['DOCUMENT_ROOT'] .'/common/creole/Creole.php');
/**
 * TODO: comprovaci�n de errores y que no salten (@funcion)
 */

class ComunicationRecep{

	//	PARAMETROS PARA LA CONEXI�N
	const PHPTYPE = 'mysqli';
 	const DBNAME =	'recepcion_bd';
 	const DBURL =	'localhost';
 	const DBUSER =	'root';//'spiderman';
 	const DBPSW =	'';//'3jvjD.pzjFcPr563';

	//POSSIBLES TYPOS DE PARAMETROS PARA LAS QUERYS.
	public static $TINT = 			1;
	public static $TFLOAT = 		2;
	public static $TSTRING = 		3;
	public static $TBOOLEAN = 		4;
	public static $TDATE = 			5;
	public static $TNULL = 			6;
	public static $TARRAY = 		7;
	public static $TBLOB = 			8;
	public static $TCLOB = 			9;
	public static $TTIME = 			10;
	public static $TTIMESTAMP = 	11;

	// Variables
	private $dns = array('phptype'  => self::PHPTYPE,
             			 'hostspec' => self::DBURL,
             			 'username' => self::DBUSER,
             			 'password' => self::DBPSW,
             			 'database' => self::DBNAME);
             			 
//	 private $dns_prueba = array('phptype'  => self::PHPTYPE,
//	 'hostspec' => self::DBURL,
//	 'username' => self::DBUSER,
//	 'password' => self::DBPSW,
//	 'database' => 'guate_bd_prueba');//

/*********************************************************************************/
/********************************** SERVICIOS ************************************/
/*********************************************************************************/

	/**
	 * Realiza una query qualquiera de forma autom�tica y devuelve el resultado en funci�n del tipo de query lanzada
	 *
	 * @param $query (String):
	 * 				Debe contener una query v�lida para ser ejecutada. (Select, Insert, Update, Delete, Alter, Create,...)
	 * 				Puede contener el numero de par�metros que sean necesarios. (par�metros = ?)
	 * @param $prepareData (Array):
	 * 				Debe informar los par�metros que haya en la query.
	 * 				Si la query no tiene par�metros no debe ser informado.
	 * 				Por defecto es un array vac�o.
	 * @param $fieldsType (String):
	 * 				Contiene los tipos de los par�metros que se han informado.
	 * 				A cada par�metro le corresponde un car�cter.
	 * 				Deben estar en el mismo orden que los par�metros pasados en $prepareData.
	 * 				En caso de no ser informado ser� rellenado de forma autom�tica,
	 * 					pero comporta una p�rdida de rendimiento
	 * 				Tipos: i (int), d (double), s (string). No se acepta 'b' (blob).
	 *
	 * @return $rs: Si todo va bien, en funci�n del tipo de query tendr� el siguiente contenido:
	 * 					SELECT: contiene el resultado (resultSet) completo de la query.
	 * 							Si no se encuentran registros
	 * 					INSERT,UPDATE,DELETE:contiene el numero de registros afectados por la query
	 * 					OTRAS: por determinar.
	 * 				Si hay fallos, es -1.
	 * 				Los fallos ocurridos se podr�n recuperar de los atributos error y errno.
	 */
/*	public function comQuery($query,$prepareData = array(),$fieldsType = 'auto') {
		$result = null;

		//conexi�n a la base de datos
		$con = @ new mysqli(self::DBURL, self::DBUSER, self::DBPSW, self::DBNAME);
		if (mysqli_connect_errno()){
			//throw new DataBaseException (mysqli_connect_error(),mysqli_connect_errno());
			throw new Exception (mysqli_connect_error(),mysqli_connect_errno());
		}

		//inicializamos el objeto stmt con la query a realizar
        $stmt = @ new mysqli_stmt($con);
        if (!$stmt->prepare($query)){
        	//throw new DataBaseException (mysqli_stmt_error($stmt),mysqli_stmt_errno($stmt));
        	throw new Exception (mysqli_stmt_error($stmt),mysqli_stmt_errno($stmt));
        }

        //Preparaci�n de los posibles par�metros de la query
        $bind_params = ($fieldsType == 'auto') ? (array) $this->fetchBindParams(array_values($prepareData)) : (array) $fieldsType;
        $params = array_merge($bind_params,$prepareData);
        @call_user_func_array(array($stmt,"bind_param") , $params);

        if ($stmt->execute()){
        	$this->numRows = mysqli_stmt_affected_rows($stmt);
	        //recuperamos los el resultado seg�n el tipo de query lanzada.
	        $exploded_query = explode (" ", $query);
	        if (strtoupper($exploded_query[0]) == 'SELECT'){
	        	//para la select, retornamos el resultset completo
	        	$result=$this->get_resultset_from_stmt($stmt);

	        }elseif (	(strtoupper($exploded_query[0]) == 'INSERT') or
	        			(strtoupper($exploded_query[0]) == 'UPDATE') or
	        			(strtoupper($exploded_query[0]) == 'DELETE')){
	        	//para inerciones, actualizaciones y borrados, retornamos el numero de registros afectados
	        	$result = $stmt->affected_rows;

	        }else{
	        	//para operaciones sobre la base de datos,
	        	//**
	        	 * 	TODO
	        	 /
	        }
        }else{
        	//fallo en la sentencia exectute
        	//throw new DataBaseException (mysqli_stmt_error($stmt),mysqli_stmt_errno($stmt));
        	throw new Exception (mysqli_stmt_error($stmt),mysqli_stmt_errno($stmt));
        }

        //cerramos los objetos
        $stmt->close();
    	$con->close();

        return $result;
	 }
*/
	public function query ($query, $prepareData = array(), $fieldsType = 'auto') {
		try {
		
//		if($_SERVER['HTTP_HOST']=='localhost:8080'){
//			$conn = @ Creole::getConnection($this->dns_prueba);
//		}
//		else{
		//conexi�n a la base de datos
		$conn = @ Creole::getConnection($this->dns);
//		}
		//inicializamos el objeto stmt con la query a realizar
		$stmt = @ $conn->prepareStatement ($query);

        //Preparaci�n de los posibles par�metros de la query
        if ($fieldsType == 'auto'){
        	//Dejamos la definici�n del tipo de los parametros a Creole
        	$this->setParamsAutomatic($stmt, $prepareData);
        }else{
        	//Definimos manualmente el tipo de los par�metros
        	$this->setParamsManually($stmt,$fieldsType,$prepareData);
        }

		$result = @ $stmt->executeQuery(ResultSet::FETCHMODE_ASSOC);

        //cerramos los objetos
        $stmt->close();
    	$conn->close();
    	
		return $result;
		}
		catch (Exception $sqle) {	
			$this->guardarError($sqle);
 			throw $sqle;
 		}
	 }
    public function guardarError($sqle){
          try{
			$this->update("INSERT INTO error values (?,1,NOW())",array($sqle->toString().$sqle->getFile().$sqle->getLine()),array(ComunicationRecep::$TSTRING));
          }catch(Exception $e){
          	echo("ERROR EN LA BASE DE DATOS!!!!!!!!!!");
          }
    } 

	public function update ($query, $prepareData = array(), $fieldsType = 'auto', &$LastInsertId = 0) {
		try {
			
//		if($_SERVER['HTTP_HOST']=='localhost:8080'){
//			$conn = @ Creole::getConnection($this->dns_prueba);
//		}
//		else{
		//conexi�n a la base de datos
		$conn = @ Creole::getConnection($this->dns);
//		}
		
		//inicializamos el objeto stmt con la query a realizar
		$stmt = @ $conn->prepareStatement ($query);

        //Preparaci�n de los posibles par�metros de la query
        if ($fieldsType == 'auto') {
        	//Dejamos la definici�n del tipo de los parametros a Creole
        	$this->setParamsAutomatic($stmt, $prepareData);
        }else{
        	//Definimos manualmente el tipo de los par�metros
        	$this->setParamsManually($stmt,$fieldsType,$prepareData);
        }

		$result = @ $stmt->executeUpdate();

		//MBV	04/12/2007
		$idGen = $conn->getIdGenerator();
		$LastInsertId=$idGen->getId();		
        
        //cerramos los objetos
        $stmt->close();
    	$conn->close();
		
		return $result;
		}
		catch (Exception $sqle) {
			$this->guardarError($sqle);
 			throw $sqle;
		}
	 }

	public function getLastInsertId(){
		$conn = @ Creole::getConnection($this->dns);

	}
/*********************************************************************************/
/******************************** FUNCIONES PRIVADAS *****************************/
/*********************************************************************************/

	private function setParamsAutomatically($stmt, $data){
		$i = 1;
		foreach ($data as $d){
			@ $stmt->set($i,$d);
			$i++;
		}
	}

	private function setParamsManually($stmt,$fieldsType,$inputValues){
		$size = count($fieldsType);
		$arrayIndex = 0;
	    for ($i=1 ; $i<=$size ; $i++){
	    	$type = $fieldsType[$arrayIndex];
	    	$value = $inputValues[$arrayIndex];
	    	$arrayIndex++;
	        switch ($type) {
	            case self::$TINT:
	            	@ $stmt->setInt($i,$value);
	                break;
	            case self::$TFLOAT:
	            	@ $stmt->setFloat($i,$value);
	                break;
				case self::$TSTRING:
	            	@ $stmt->setString($i,$value);
	                break;
	            case self::$TBOOLEAN:
	            	@ $stmt->setBoolean($i,$value);
	                break;
	            case self::$TDATE:
	            	@ $stmt->setDate($i,$value);
	                break;
	            case self::$TNULL:
	            	@ $stmt->setNull($i,$value);
	                break;
	            case self::$TARRAY:
	            	@ $stmt->setArray($i,$value);
	                break;
	            case self::$TBLOB:
	            	@ $stmt->setBlob($i,$value);
	                break;
	            case self::$TCLOB:
	            	@ $stmt->setClob($i,$value);
	                break;
	            case self::$TTIME:
					@ $stmt->setTime($i,$value);
	                break;
	            case self::$TTIMESTAMP:
	            	@ $stmt->setTimeStamp($i,$value);
	                break;
	            default://unknown type,
	            	@ $stmt->set($i,$value);
	        }
        }
        return;
	}
/*	private function fetchBindParmsc ($inputValues){
	    $return = array();
	    foreach ($inputValues as $value) {
	        switch (true) {
	            case is_integer($value):
	                    $return[]= 'i';
	                break;
	            case is_double($value):
	                    $return[]= 'd';
	                break;
	            case is_string($value):
	                    $return[]= 's';
	                break;
	            case is_null($value):
	            case is_array($value):
	            case is_object($value):
	            case is_resource($value):
	            case is_bool($value):
	            	throw new Exception("Unacceptable type used for bind_param.");
	            default://unknown type,
	            	throw new Exception("Unknown type used for bind_param.");
	                    break;
	        }
        }
        return $return;
	}
*/
	/**
	 *	Dado un array de par�metros devuelve la cadena de tipos.
	 *
	 *	@param $inputValues (Array):
	 *				Contiene los valores a sustituir de la Prepared Statement,
	 *					de los que hay que sacar los tipos
	 *
	 *	@return $return (String):
	 *				Contiene la cadena de tipos correspondiente a los valores de entrada.
	 *
	 *	Ejemplo:  array (95, 'pelota', 598.2)  --> 'isd' (int/string/double)
	 */
/*	private function fetchBindParams($inputValues){
	    $return = '';
	    foreach ($inputValues as $value) {
	        switch (true) {
	            case is_integer($value):
	                    $return .= 'i';
	                break;
	            case is_double($value):
	                    $return .= 'd';
	                break;
	            case is_string($value):
	                    $return .= 's';
	                break;
	            case is_null($value):
	            case is_array($value):
	            case is_object($value):
	            case is_resource($value):
	            case is_bool($value):
	            	throw new Exception("Unacceptable type used for bind_param.");
	            default://unknown type,
	            	throw new Exception("Unknown type used for bind_param.");
	                    break;
	        }
        }
        return $return;
    }
*/
	/**
	 * Recupera el resultset completo de una query ejecutada sobre un STMT
	 *
	 * @param $stmt (object):
	 * 				Prepared Statement ejecutado.
	 *
	 * @return $res (Array):
	 * 				resultset de la query ejecutada sobre $stmt
	 * 				-1: En caso de haber errores. En ese caso almacena los errores en los
	 * 					atributos de la clase error y errno
	 *
	 *
	 * Nota: deve ser ejecutado despu�s de execute.
	 */
/*	private function get_resultset_from_stmt($stmt){
		$results="";
		if (!$stmt->store_result()){
			//throw new DataBaseException (mysqli_stmt_error($stmt),mysqli_stmt_errno($stmt));
			throw new Exception (mysqli_stmt_error($stmt),mysqli_stmt_errno($stmt));
		}

        $meta = $stmt->result_metadata();
        while ($column = $meta->fetch_field()) {
            $columnName = str_replace(' ', '_', $column->name);
            $bindVarArray[] = &$results[$columnName];
        }
        @call_user_func_array(array($stmt, 'bind_result'), $bindVarArray);

		while ($stmt->fetch()){
			foreach ($results as $k => $v) {
            	$res[$k] = $v;
        	}
			$resultset[]=$res;
		}
		$stmt->free_result();

		return $resultset;
	}
*/
/*
Ejemplos de utilizaci�n de la clase.

Como lanzar una query sin par�metros
	$query = "Select * from usuarios";
	$data = $this->com_query($query);

Como lanzar una query con par�metros
	$query = "SELECT * FROM usuarios where nickname = ?";
	$data = $this->com_query($query,array("riverinyo"),'s');


*/
}
?>
