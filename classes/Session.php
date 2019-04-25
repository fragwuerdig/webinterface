<?php

class Session implements ISession{
	
	protected $cookie_lifetime = 0;
	protected $cookie_path = "/";
	protected $cookie_domain = null;
	protected $cookie_secure = false;
	protected $cookie_httponly = true;
	protected $save_handler = null;
	
	protected $sname = "PHPSESSID";
	protected $sid = "";
	protected $sdata = null;
	
	public function __construct(){
		
		$this->cookie_domain = $_SERVER['SERVER_NAME'];
		
		if (!isset($_SERVER['HTTPS']))
			$this->cookie_secure = true;
		
		ini_set('session.use_cookies',1);
		ini_set('session.use_only_cookies',1);
		ini_set('session.cookie_lifetime',$this->cookie_lifetime);
		ini_set('session.cookie_domain',$this->cookie_domain);
		
		if ($this->cookie_httponly)
			ini_set('session.cookie_httponly',1);
		else
			ini_set('session.cookie_httponly',0);
		
		
		$this->save_handler = new SessionHandler();
		session_set_save_handler($this->save_handler);
		
		// Start Session and set variables
		if (!$this->open())
			throw new RuntimeException('No Session Opened');
		
	}
	
	public function __get($name){
		
		if (empty($this->sid))
			return false;
		if (isset($_SESSION[$name]))
			return $_SESSION[$name];
		return false;
		
	}
	
	public function __set($name,$value){
		
		if (empty($this->sid))
			return false;
		$_SESSION[$name] = $value;
		return true;
		
	}
	
	public function __isset($name){
		
		if (empty($this->sid))
			return false;
		if (isset($_SESSION[$name]))
			return true;
		return false;
		
	}
	
	public function __unset($name){
		
		if (empty($this->sid))
			return false;
		if (isset($_SESSION[$name]))
			unset($_SESSION[$name]);
		return true;
		
	}
	
	public function open() {
		
		session_name($this->sname);
		if (!session_start())
			return false;
		$this->sid = session_id();
		return true;
		
	}
	
	public function close() {
		
		if (!empty($this->sid)){
			session_name($this->sname);
			$id = $this->sid;
			session_unset();
			if (session_destroy()){
				setcookie (session_id(), "", time() - 3600);
				session_destroy();
				session_write_close();
				return true;
			}
		}
		return false;
		
	}

	
}

?>
