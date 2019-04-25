<?php

class VirtualUser extends Entity {
	
	public function __construct(array $data){
	
		array_push($this->allowedVariables,"domain","email","password");
		parent::__construct($data);
	
	}
	
	public function setEmail(Email $email){
		
		if ($email->isValid()){
			$this->variables['email'] = $email;
			return true;
		}
		return false;
		
	}
	
	public function toArray(){
		
		$data = parent::toArray();
		$data['password'] = $data['password']->toString();
		$data['email'] = $data['email']->toString();
		return $data;
	
	}

}

?>
