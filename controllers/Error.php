<?php

abstract class Error implements IError {
	
	protected $errController;
	protected $errAction;
	protected $errStr;
	protected $errArg;
	
	public function __construct(Controller $controller, $action = 'defaultAction', array $arg = [], $errstr='Generic Error occurred.'){
	
		$this->errStr = $errstr;
		$this->errController = $controller;
		$this->errAction = $action;
		$this->errArg = $arg;
	
	}
	
	public function getErr(){
	
		return $this->errstr;
	}
	
	public function handle(){
		
		$controller = $this->errController;
		$action = $this->errAction;
		return $controller->$action($this->errArg);
	
	}
	
}

?>
