<?php

class Controller {
	
	protected $db;
	protected $phpsess;
	
	public function __construct(IPLayer $db, Session $phpsession){
		
		$this->db = $db;
		$this->phpsess = $phpsession;
		
	}
	
}

?>
