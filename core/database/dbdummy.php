<?php
require_once(_ADMIN_PATH."/libs/db/".$GLOBALS["CONEXION"]->driver.".php");
/******************************
	Clase para el manejar
  	  de Base de Datos
******************************/

class DB2{
	//Class attributes
	var $id = 0;
	var $count = 0;
	var $sqlDebug = false;
	
	//DB Connection attributes
	var $table = "";
	var $join = ""; 
	var $idSelector = "";
	var $select_fields = "*";
	var $db_connection; #connection flag
	private $connection;
	
	//Insert attributes
	var $inserted_id; #Last inserted record's ID
	
	//Query attributes
	var $Where = "";
	var $group = "";
	var $order = "";
	var $limit = "";
	var $page = 1;
	
	var $autoCount = false;
	var $errorCode = 0;
	
	function DB(){
		$this->getConnection();
	}
	
	function getConnection(){
		$host = $GLOBALS["CONEXION"]->host;
		$usuario = $GLOBALS["CONEXION"]->usuario;
		$password = $GLOBALS["CONEXION"]->password;
		$db = $GLOBALS["CONEXION"]->database;

		$this->connection = DBHandler::connect($host,$usuario,$password);
		if($this->connection){
			$database = DBHandler::select_database($this->connection, $db);
			if($database){
				$this->db_connection = true;
			}
			else
				$this->db_connection = false;
		}else
			$this->db_connection = false;
	}


	function insert($data,$addQuotes=true){
		$fields = "";
		$values = "";
	
		//Obtiene los campos y values correspondientes y los separa en cadenas distintas
		foreach($data as $i=>$value){
			$fields .= $i.",";
			if($addQuotes)
				$values .= "'".$value."',";
			else
				$values .= $value.",";
		}
		//$fields contiene los campos en los que se insertaran los values
		$fields = substr($fields,0,strlen($fields)-1);
		//$values contiene los values a insertar en cada campo
		$values = substr($values,0,strlen($values)-1);
		
		$sql = "INSERT INTO ".$this->table." ($fields) VALUES ($values)";
		
		if($this->Execute($sql))
		{
			#Obtiene el id agregado mediante auto_increment
			$this->inserted_id = DBHandler::insert_id($this->connection);
			return true;			
		}
		else
			return false;
			
	}

	function insertarSelect($valuesArray,$from){
		$fields = "";
		$values = "";
	
		//Obtiene los campos y values correspondientes y los separa en cadenas distintas
		foreach($valuesArray as $i=>$value){
			$fields .= $i.",";
			$values .= $value.",";
		}
		//$fields contiene los campos en los que se insertaran los values
		$fields = substr($fields,0,strlen($fields)-1);
		//$values contiene los values a insertar en cada campo
		$values = substr($values,0,strlen($values)-1);
		
		$sql = "INSERT INTO ".$this->table." ($fields) SELECT $values FROM $from";
		
		if($this->Execute($sql))
		{
			#Obtiene el id agregado mediante auto_increment
			$this->inserted_id = DBHandler::insert_id($this->connection);
			return true;			
		}
		else
			return false;
	}
	
	function delete($field="",$where=""){	
		if(trim($field==""))	
		 $field = $this->id;
		 
		$this->Where = $this->idSelector." IN (".$field.")";
		if($where){
			$this->Where .= " AND (".$where.")";	
		}
		$sql = "DELETE FROM ".$this->table." WHERE ".$this->Where;
		
		if($this->Execute($sql))
			return true;
		else
			return false;
		
	}
	
	function logicDelete($id="",$field = "deleted",$where="")
	{
		$fields[$field] = 1;
		return $this->udpateIn($fields,$id,$where="");
	}
	
	function update($fields){		
			
		$values = "";
		foreach($fields as $field => $valor){
			$values .= $field ." = '".$valor."', ";
		}
		
		$values = substr($values,0,strlen($values)-2);
		
		$sql = "UPDATE ".$this->table." SET ".$values." WHERE ".$this->idSelector." = '".$this->id."'"; 	
		
		if($this->Execute($sql))
			return true;
		else
			return false;
	}
	
	function udpateIn($fields,$id,$where=""){
			
		$values = "";
		foreach($fields as $field => $valor){
			$values .= $field ." = '".$valor."', ";
		}
		
		//Elimina la Ultima coma (,)
		$values = substr($values,0,strlen($values)-2);
		
		$this->Where = $this->idSelector." IN (".$id.")";
		if($where){
			$this->Where .= " AND (".$where.")";	
		}
		
		$sql = "UPDATE ".$this->table." SET ".$values." WHERE ".$this->Where; 	
		
		if($this->Execute($sql))
			return true;
		else
			return false;
	}
	
	function get($sql = "")
	{
		if($sql == "")
		{
			$sql = "SELECT ".$this->select_fields." FROM ".$this->table." ".$this->join ;
			if($this->Where != "")
				$sql .= " WHERE ".$this->Where;
			if($this->group != "")
				$sql .= " GROUP BY ".$this->group;
			if($this->order != "")
				$sql .= " ORDER BY ".$this->order;
			if($this->limit != "")
				$sql .= " LIMIT ".(($this->page-1)*$this->limit).",".$this->limit;
		}
				
			$regs = $this->Execute($sql);
			$this->count = @DBHandler::num_rows($regs);
			
			if(!$this->count)
				$this->count = 0;
									
			if($this->autoCount){
				$this->countData();
			}
			
			if($this->count > 0)
			{
				$i = 0;			
				$records = array();
					while($registro = DBHandler::fetch_array($regs)){
						foreach($registro as $field => $valor)
							if(!is_numeric($field))							
								$records[$i]->$field = $valor;
						#Asigna values extra al registro (ej. values que requieren alguna formula)				
						$records[$i] = $this->extra($records[$i]);	
						$i +=1;
					}
				return $records;
			}
			else
				return NULL;

	}
	
	#Devuelve el total de records, sin limites
	function listCount($sql = "")
	{
		if($sql == "")
		{
			$sql = "SELECT COUNT(".$this->idSelector.") AS cantidad FROM ".$this->table." ".$this->join ;
			if($this->Where != "")
				$sql .= " WHERE ".$this->Where;
			//if($this->group != "")
				//$sql .= " GROUP BY ".$this->group;
			//if($this->order != "")
				//$sql .= " ORDER BY ".$this->order;			
		}
				
			$regs = $this->Execute($sql);
			$count = @DBHandler::num_rows($regs);			
			
			if(!$count || $count == 0)
				return 0;
			
			$i = 0;			
			$records = array();
				while($registro = DBHandler::fetch_object($regs)){
					$count = $registro->cantidad;
				}
			return (int)$count;
	}
	
	#Asigna al objeto los atributos para paginado
	function countData(){
		$this->pagination->total = $this->listCount();
		$this->pagination->from = $this->page * $this->limit + 1;
		$this->pagination->to = $this->page * $this->limit + $this->count;
		if($this->limit != "" && $this->limit > 0 && $this->pagination->total > 0)
			$this->pagination->pages = (int)ceil($this->pagination->total / $this->limit);
		else
			$this->pagination->pages = 0;
	}

	#Determina si un registro existe en la BD para evitar duplicidad
	function Exists($valor,$field = "name", $operador = "LIKE",$notDeleted=false)
	{	
		$del = $notDeleted?" AND deleted = 0 ":"";	
		$Registro = $this->get("SELECT ".$this->idSelector." FROM ".$this->table." WHERE ".$field." ".$operador." '".$valor."' ".$del."LIMIT 1");			
		return (count($Registro)>0 && ( ($Registro[0]->id) != $this->id ));

	}

	#Asigna values extra al registro
	function extra($registro)
	{
		#Asigna el atributo id de acuerdo al valor en el idSelector
		$idSelector = $this->idSelector;
		$registro->id = $registro->$idSelector;
		return $registro;
	}

	#Carga los values del registro que cumpla la condicion y lo asigna como atributos del objeto
	function find($id = "")
	{
		if($id != "")
			$this->id = $id;
		
		$sql = "SELECT ".$this->select_fields." FROM ".$this->table." ".$this->join ." ";
		$sql .= "WHERE (".$this->idSelector." = '".$this->id."')";
		if($this->Where != "")
			$sql .= " AND ".$this->Where;		
		if($this->group != "")
			$sql .= " GROUP BY ".$this->group;
		if($this->order != "")
			$sql .= " ORDER BY ".$this->order;
		$sql .= " LIMIT 1";
	
		$regs = $this->Execute($sql);
		$this->count = @DBHandler::num_rows($regs);
		
		if(!$this->count)
			$this->count = 0;
			
		/*******************************************
					Cargar Valores
		*******************************************/
		if($this->count > 0)
		{
			$i = 0;			
				while($registro = DBHandler::fetch_array($regs)){
					foreach($registro as $field => $valor)	
						if(!is_numeric($field))						
							$this->data->$field = $valor;				
				}
		}
		
		$this->data = $this->extra($this->data);
		
		return $this->data;
	}
	
	function Group($records,$field){
		$data = array();
		if(!is_array($records))
			return $records;

		foreach($records as $registro)
		{
			if(!isset($data[$registro->$field]))
				$data[$registro->$field] = $registro;
			
			$data[$registro->$field]->elementos[] = $registro;						
		}
		
		return $data;
	}
	
	function cutText($str, $len)
	{
		if(strlen($str)<=$len)
			return $str;
		else
			return (substr($str,0,$len-3)."...");
	}
	
	/*******************************************
		FUNCIONES PARA EL MANEJO DE QUERIES
	*******************************************/
	
	function addWhere($fields,$operador = "=",$logico = "OR", $whereJoin = "AND")
	{
		$where = "";
		foreach($fields as $field => $value){
			if(strtolower($operador)=="like")
				$value = "'%".$value."%'";		
			$where .= $field ." ". $operador ." " . $value . " ".$logico." ";
		}

		if($where!=''){
			if($this->Where != ''){
				$this->Where .= ' '.$whereJoin.' ';
			}
			
			//Elimina el operador logico sobrante
			$this->Where .= '('.substr($where,0,strlen($where)-(strlen($logico)+2)).')';
		}

	}
		
	
	function Execute($sql)
	{
		$inicio = microtime();
		$resultado = @DBHandler::query($this->connection, $sql);
		$filas = DBHandler::affected_rows($this->connection);
		if($this->sqlDebug == true)	
		{
			$fin = microtime();
			echo "<hr/>".$GLOBALS["CONEXION"]->driver.": ".$sql." (".round($fin-$inicio,4)." seg) ".$filas." fila(s) afectada(s)<hr/>";							
			if(DBHandler::error($this->connection))
				echo "<b>Error:</b> ".DBHandler::error($this->connection)."<hr/>";
		}
		return $resultado;
	}
	
	function debug($flag = true)
	{
		$this->sqlDebug = $flag;
	}

	function textoCorto($texto,$caracteres)
	{
		return strlen($texto)<=$caracteres?$texto:substr($texto,0,$caracteres-2).'...';
	}

	/* MySQL Transactions */
	function begin(){
		$this->Execute("START TRANSACTION");
	}

	function commit(){
		$this->Execute("COMMIT");
	}

	function rollback(){
		$this->Execute("ROLLBACK");
	}
}