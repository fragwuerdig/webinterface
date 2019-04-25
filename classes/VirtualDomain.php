<?php

class VirtualDomain extends Entity {
	
	public function __construct(array $data){
	
		array_push($this->allowedVariables,"name");
		parent::__construct($data);
	
	}
	
	public function setName($name){
		
		if (!is_string($name) || strlen($name) < 2 || strlen($name) > 50)
			throw new InvalidArgumentException('Invalid name.');
		$this->variables['name'] = $name;
		
	}
	
	public function getName(){
		
		if (!isset($this->variables['name']))
			return false;
		return $this->variables['name'];
	
	}

}

?>
