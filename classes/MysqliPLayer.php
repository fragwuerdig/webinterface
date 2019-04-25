	<?php

class MysqliPLayer implements IPLayer {
	
	protected $dbResult;
	protected $dbmsObj;
	protected $dbCredentials;
	
	public function __construct(){
		
		$this->dbCredentials = array(
			'host' => '10.17.19.2',
			'username' => 'mailuser',
			'password' => 'some_password',
			'database' => 'mailserver');
		
	}
	
	/*
	public function delete($locator,$rowCond);
	*/
	
	protected function getMysqlErrStr(){
	
		$error = "MySQL Error ";
		$error .= $this->dbmsObj->errno." -- ";
		$error .= $this->dbmsObj->error;
		return $error;
	
	}
	
	protected function connect(){
		
		if (!is_null($this->dbmsObj)){
			if ($this->dbmsObj->ping()) 
				return true;
		}
		$this->dbmsObj = new mysqli(
			$this->dbCredentials['host'],
			$this->dbCredentials['username'],
			$this->dbCredentials['password'],
			$this->dbCredentials['database']);
		if ($this->dbmsObj->errno)
			throw new RuntimeException($this->getMysqlErrStr());
		return true;
		
	}
	
	private function disconnect(){
		
		if (isset($dbmsObj))
			$dbmsObj->close();
		$dbmsObj = null;
		
	}
	
	protected function checkCredentials(array $credentials){
		
		$valid = isset($credentials['user']);
		$valid = $valid && isset($credentials['password']);
		$valid = $valid && isset($credentials['host']);
		$valid = $valid && is_string($credentials['user']);
		$valid = $valid && is_string($credentials['password']);
		$valid = $valid && is_string($credentials['host']);
		return $valid;
		
	}
	
	public function setCredentials(array $credentials){
		
		if ($this->checkCredentials($credentials))
			$this->dbCredentials = $credentials;
		else
			return false;
		return true;
		
	}
	
	public function __destruct() {
	
		$this->disconnect();
	
	}
	
	public function delete($table,array $conds){
		
		if (!is_string($table))
			throw new InvalidArgumentException('Invalid Table given.');
		$query = "DELETE FROM ".$table;
		$query .= $this->parseCond(" AND ", $conds);
		echo $query;
		$this->connect();
		$result = $this->dbmsObj->query($query);
		$this->disconnect();
		$this->verifyQuery($result);
		
	}
	
	public function insert($table,array $data){
		
		if (!is_string($table))
			throw new InvalidArgumentException('Invalid Table given.');
		$escapedKeys = [];
		$escapedValues = [];
		$this->connect();
		foreach($data as $key => $value){
			array_push($escapedKeys,$this->dbmsObj->real_escape_string($key));
			array_push($escapedValues,$this->quote($value));
		}
		$escapedKeys = "(".implode(",",$escapedKeys).")";
		$escapedValues = "(".implode(",",$escapedValues).")";
		$query = "INSERT INTO ".$this->dbmsObj->real_escape_string($table);
		$query .= " ".$escapedKeys." VALUES ".$escapedValues;
		$result = $this->dbmsObj->query($query);
		$this->disconnect();
		$this->verifyQuery($result);
		return true;
	
	}
	
	protected function quote($value) {
		
		$this->connect();
		$ret = "'".$this->dbmsObj->real_escape_string($value)."'";
		$this->disconnect();
		return $ret;
		
	}
	
	protected function parseCond($glue, array $conds) {
	
		$query = "";
		if (!empty($conds)) {
			$query .= " WHERE ";
			foreach ($conds as $key => $cond){
				$cond = explode("=",$cond);
				$cond[1] = $this->quote($cond[1]);
				$cond = implode("=",$cond);
				$conds[$key] = $cond;
			}
			$query .= implode($glue, $conds); 
		}
		return $query;
	
	}
	
	public function select($table,array $conds = [],array $cols = ['*']) {
		
		if (!is_string($table))
			throw new InvalidArgumentException('Table has to be given as string.');
		foreach ($cols as $str){
			if (!is_string($str))
				throw new InvalidArgumentException('Conditions habe to be given as string.');
		}
		$this->connect();
		$query = "SELECT ".$this->dbmsObj->real_escape_string(implode(",",$cols));
		$query .= " FROM ".$this->dbmsObj->real_escape_string($table);
		$query .= $this->parseCond(" AND ",$conds);
		$result = $this->dbmsObj->query($query);
		$this->disconnect();
		$this->verifyQuery($result);
		return true;
	
	}
	
	public function update($table,array $data,array $conds = []){
		
		if (!is_string($table))
			throw new InvalidArgumentException('Table has to be given as string.');
		$query = "UPDATE ".$this->dbmsObj->real_escape_string($table);
		$query .= " SET ";
		$this->connect();
		$setters = [];
		foreach($data as $key => $value){
			$key = $this->dbmsObj->real_escape_string($key);
			$value = $this->quote($value);
			array_push($setters,$key."=".$value);
		}
		$query .= implode(",",$setters);
		$query .= $this->parseCond(" AND ",$conds);
		$result = $this->dbmsObj->query($query);
		$this->disconnect();
		$this->verifyQuery($result);
		return true;
		
	}
	
	protected function verifyQuery($result) {
		
		if ($this->dbmsObj->errno)
			throw new RuntimeException($this->getMysqlErrStr());
		$this->dbResult = $result;
		
	}
	
	public function fetch() {
		
		if (isset($this->dbResult)){
			$row = $this->dbResult->fetch_assoc();
			if ($row)
				return $row;
			else {
				$this->dbResult->free();
				$this->dbResult = null;
			}
		}
		return false;
		
	}
	
}

?>
