
<?php

abstract class Entity implements IEntity {
	
	protected $allowedVariables = array();
	protected $variables = array();
	
	public function __construct(array $data){
		
		array_unshift($this->allowedVariables,'id');
		if (array_keys($data) == $this->allowedVariables)
			$this->variables = $data;
		else
			throw new InvalidArgumentException('Input data does not match allowed fields!');
		
	}
	
	public function getTableName(){
		
		return self::$tableName;
	
	}
	
	protected function invalidField($fieldName) {
		
		if(!in_array($fieldName,$this->allowedVariables)){
			$string = get_class($this)." has no field named '".$fieldName."'.";
			throw new InvalidArgumentException($string);
		}
		
	}
	
	protected function isValidMutator($mutator) {
			
			$isValidMutator = method_exists($this,$mutator);
			$isValidMutator = $isValidMutator && is_callable(array($this,$mutator));
			return $isValidMutator;
			
	}
	
	public function __set($name,$value) {
		
		$this->invalidField($name);
		$mutator = "set".ucfirst($name);
		if($this->isValidMutator($mutator))
			$this->$mutator($value);
		else
			$this->variables[$name] = $value;
	
	}
	
	public function __get($name) {
	
		$this->invalidField($name);
		$mutator = "get".ucfirst($name);
		if (!isset($this->variables[$name]))
			throw new InvalidArgumentException('Value not set yet.');
		if($this->isValidMutator($mutator))
			return $this->$mutator();
		return $this->variables[$name];
	
	}
	
	public function __isset($name) {
		
		$this->invalidField($name);
		return isset($this->variables[$name]);
		
	}
	
	public function __unset($name) {
		
		$this->invalidField($name);
		unset($this->variables[$name]);
		
	}
	
	public function getFields() {
		
		return $this->allowedVariables;
		
	}
	
	public function setId($id){
		
		if (!filter_var($id,FILTER_VALIDATE_INT,array('options'=>array('min_range'=>1,'max_range'=>99999999999))))
			throw new InvalidArgumentException('Invalid id.');
		$this->variables['id'] = $id;
		
	}
	
	public function getId(){
		
		if (!isset($this->variables['id']))
			return false;
		return $this->variables['id'];
	
	}
	
	public function toArray(){
	
		return $this->variables;
	
	}
	
}

?>
