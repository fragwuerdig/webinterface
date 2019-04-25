<?php

class LoginError extends Error {
	
	public function __construct(Controller $controller, array $args, $errstr='Login Error Occured'){
	
		parent::__construct($controller, 'showLoginPage', $args, $errstr);
	
	}
	
}

?>
