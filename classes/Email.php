
<?php

class Email {
	
	protected $email;
	
	public function __construct($email){
		
		if (!is_string($email) || strlen($email) < 1)
			throw new InvalidArgumentException('Email must be non-null string.');
		$this->email = $email;
	
	}
	
	public function isValid(){
		
		if (!is_string($this->email))
			return false;
		if (!filter_var($this->email,FILTER_VALIDATE_EMAIL))
			return false;
		return true;
		
	}
	
	public function toString() {
	
		return $this->email;
	
	}
	
	public function set($email) {
		
		$old = $this->email;
		$this->email = $email;
		if ($this->isValid())
			return true;
		$this->email = $old;
		return false;
		
	}
	
}

?>
