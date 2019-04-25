<?php

class VirtualAlias extends Entity {
	
	public function __construct(array $data){
	
		$this->allowedVariables = ["domain","source","destination"];
		parent::__construct($data);
	
	}
	
	
	public function setDomain($id){
		
		if (!filter_var($id,FILTER_VALIDATE_INT,array('options'=>array('min_range'=>1,'max_range'=>99999999999))))
			throw new InvalidArgumentException('Invalid domain_id.');
		$this->variables['domain_id'] = $id;
		
	}
	
	public function setSource(Email $source){
		
		if ($source->isValid()){
			$this->variables['source'] = $source;
			return true;
		}
		return false;
		
	}
	
	public function setDestination(EmailCollection $destination){
	
		if ($destination->isValid()){
			$this->variables['destination'] = $destination;
			return true;
		}
		return false;
	
	}
	
	public function getDestination() {
		
		if (isset($this->variables['destination']))
			return $this->variables['destination'];
		return false;
		
	}
	
	public function toArray(){
		
		$data = parent::toArray();
		$data['source'] = $data['source']->toString();
		$data['destination'] = implode(",",$data['destination']->toStringArray());
		return $data;
		
	}

}

?>
