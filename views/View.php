<?php

abstract class View implements IView {
	
	protected $param = array();
	protected $template = '';
	protected $folder = '';
	
	public function __construct(array $params = null) {
		
		$DS = DIRECTORY_SEPARATOR;
		$this->folder = Constants::ROOT.'templates'.$DS;
		$this->template = get_class($this);
		$this->template = str_replace('View','Template',$this->template);
		if (!is_null($params)){
			$this->param = $params;
		}
		
	}
	
	public function __get($name){
	
		if (isset($this->param[$name]))
			return $this->param[$name];
		else
			return false;
	
	}
	
	public function __set($name,$value) {
	
		$this->param[$name] = $value;
	
	}
	
	public function __unset($name){
		
		unset($this->param[$name]);
		
	}
	
	public function __isset($name){
		
		if(isset($this->param[$name]))
			return true;
		return false;
		
	}
	
	public function display(){
		
		$filename = $this->folder.$this->template.'.php';
		
		if (file_exists($filename)) {
			ob_start();
			$_ = $this->param;
			include($filename);
			$viewstring = ob_get_contents();
			ob_end_clean();
		}
		else
			$viewstring = 'Template not found';
		
		echo $viewstring;
		
	}

}

?>
