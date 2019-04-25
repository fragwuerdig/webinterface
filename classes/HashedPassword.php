
<?php

class HashedPassword {
	
	protected $hash;
	protected $salt;
	
	function __construct(){
		
		$this->hash = '';
		$this->salt = ''; 
		
	}
	
	public function setHash($hash){
		
		$matches = [];
		preg_match('~^\{SHA256-CRYPT\}(\$5\$[0-9a-zA-Z]{16})\$(.*)$~',$hash,$matches);
		$salt = $matches[1];
		$hash = $matches[2];
		$this->hash = $salt."$".$hash;
		$this->salt = $salt;
		
	}
	
	public function setPlainText($pass,$salt=''){
	
		if (empty($salt)){
			$salt = [];
			$alpha = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
			$alpha = str_split($alpha,1);
			shuffle($alpha);
			for($i=0;$i<16;$i++){
				$j = rand(0,count($alpha)-1);
				array_push($salt,$alpha[$j]);
			}
			$salt = '$5$'.implode("",$salt);
		}
		$this->hash = crypt($pass,$salt);
		$this->salt = $salt;
	
	}
	
	public function getSalt(){
		
		return $this->salt;
		
	}
	
	public function toString() {
	
		return "{SHA256-CRYPT}".$this->hash;
	
	}
	
}

?>
