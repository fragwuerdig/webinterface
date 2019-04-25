<?php

class UserAddError extends Error {
	
	public function __construct(Controller $controller, array $args, $errstr='Error while editing/adding User'){
	
		parent::__construct($controller, 'showUsers', $args, $errstr);
	
	}
	
}

?>
