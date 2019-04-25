
<?php

class EmailCollection{
	
	protected $emails = [];
	
	public function __construct(array $emails){
		
		foreach ($emails as $email){
			if (!is_string($email) || strlen($email) < 1)
				throw new InvalidArgumentException('Email must be non-null string.');
			$email = new Email($email);
			array_push($this->emails,$email);
		}
	
	}
	
	public function isValid(){
		
		foreach ($this->emails as $email){
			if (!$email->isValid())
				return false;
		}
		return true;
		
	}
	
	public function toStringArray() {
	
		$stringarray = [];
		foreach ($this->emails as $email)
			array_push($stringarray,$email->toString());
		return $stringarray;
	
	}
	
	public function set($emails) {
		
		$old = $this->emails;
		$this->emails = [];
		foreach($emails as $email){
			$email = new Email($email);
			if (!$email->isValid()){
				$this->emails = $old;
				return false;
			}
			array_push($this->emails,$email);
		}
		return true;
		
	}
	
	public function add($email) {
		
		$email = new Email($email);
		if (!$email->isValid())
			return false;
		array_push($this->emails,$email);
		return true;
		
	}
	
	public function del($email) {
	
		$email = new Email($email);
		var_dump(array_search($email,$this->emails));
		$key = array_search($email,$this->emails);
		if (isset($this->emails[$key]))
			unset($this->emails[$key]);
	
	}
	
	public function count() {
		
		var_dump($this->emails);
		return count($this->emails);
		
	}
	
}

?>
