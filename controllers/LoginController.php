
<?php

class LoginController extends Controller {
	
	public function defaultAction(array $post){
		
		return $this->showLoginPage($post);
	
	}
	
	public function showLoginPage(array $errors = []){
		
		$view = new LoginView($errors);
		$view->display();
		return true;
		
	}
	
	public function doLogin(array $args = []){
		
		if (empty($args['username']) || empty($args['password'])){
			return new LoginError($this,['Username and Password have to be provided']);
		}
		$username = $args['username'];
		$password = $args['password'];
		$mapper = new VirtualUserMapper($this->db);
		$mapper->select(['email='.$username]);
		$user = $mapper->fetchEntity();
		if (!$user){
			return new LoginError($this,['User not found.']);
		}
		$userProvided = new HashedPassword();
		$userProvided->setPlainText($password,$user->password->getSalt());
		if ($userProvided != $user->password){
			return new LoginError($this,['Password wrong.']);
		}
		else{
			$this->phpsess->loginid = $user->id;
			return true;
		}
		return new LoginError($this,['Something bad happened while login']);
	
	}
	
}

?>
