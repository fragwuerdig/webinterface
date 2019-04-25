
<?php

class SessionController extends Controller {
	
	
	public function doLogout(){
	
		unset($this->phpsess->loginid);
		header('Location: index.php?controller=LoginController&action=showLoginPage');
		return true;
	
	}
	
	public function deleteUser(array $arg = []){
		
		echo "hallo";
		$id = $arg['id'];
		$mapper = new VirtualUserMapper($this->db);
		$mapper->delete(intval($id));
		return true;
		
	}
	
	public function setUserPassword(array $args = []){
		
		if (empty($args['password']))
			return new UserAddError($this,['Please provide a password to use']);
		$id = $args['id'];
		$password = $args['password'];
		$hash = new HashedPassword();
		$hash->setPlainText($password);
		$mapper = new VirtualUserMapper($this->db);
		$mapper->select(["id=".$id]);
		$user = $mapper->fetchEntity();
		$user->password = $hash;
		$mapper->save($user);
		return true;
		
	}
	
	protected function getMenu() {
		
		return array(
			'Logout' => 'index.php?controller=SessionController&action=doLogout',
			'Show/Edit Mailusers' => 'index.php?controller=SessionController&action=showUsers',
			'Show/Edit Aliases' => 'index.php?controller=SessionController&action=showAliases'
		);
		
	}
	
	public function showUsers(array $message = []) {
		
		// Fetch Users
		$mapper = new VirtualUserMapper($this->db);
		$mapper->select();
		$userArray = [];
		while ($user = $mapper->fetchEntity())
			array_push($userArray,$user);
			
		// Fetch Possible Domains
		$mapper = new VirtualDomainMapper($this->db);
		$mapper->select();
		$domainArray = [];
		while ($domain = $mapper->fetchEntity())
			array_push($domainArray,$domain);
		
		// Render View
		$view = new SessionView();
		$view->menu = $this->getMenu();
		$view->content = new ShowUsersView(array('users' => $userArray, 'message' => $message, 'domains' => $domainArray));
		$view->display();
		return true;
		
	}
	
	public function showAliases(array $message = []) {
	
		$mapper = new VirtualAliasMapper($this->db);
		$mapper->select();
		$aliasArray = [];
		while ($alias = $mapper->fetchEntity())
			array_push($aliasArray,$alias);
			
		$mapper = new VirtualDomainMapper($this->db);
		$mapper->select();
		$domainArray = [];
		while ($domain = $mapper->fetchEntity())
			array_push($domainArray,$domain);
			
		$view = new SessionView();
		$view->menu = $this->getMenu();
		$view->content = new ShowAliasesView(array('aliases'=>$aliasArray, 'domains'=>$domainArray, 'message' => $message));
		$view->display();
		return true;
	
	}
	
	public function deleteAlias(array $args = []){
		
		$source = $args['source'];
		$destination = $args['destination'];
		$aliasMapper = new VirtualAliasMapper($this->db);
		$aliasMapper->select(['source='.$source]);
		$alias = $aliasMapper->fetchEntity();
		$alias->destination->del($destination);
		if ($alias->destination->count() == 0)
			$aliasMapper->delete($alias->id);
		else
			$aliasMapper->save($alias);
		return true;
		
	}
	
	public function addAliasToList(array $args = []){
		
		if (empty($args['destination']))
			return new AliasAddError($this,['No email given.']);
		$email = new Email($args['destination']);
		if (!$email->isValid())
			return new AliasAddError($this,['Not a valid email address']);
		$mapper = new VirtualAliasMapper($this->db);
		$mapper->select(['source='.$args['source']]);
		$alias = $mapper->fetchEntity();
		$alias->destination->add($args['destination']);
		$mapper->save($alias);
		return true;
		
	}
	
	public function addAlias(array $args = []) {
		
		if (empty($args['emailprefix']) || empty($args['destination']))
			return new AliasAddError($this,['Please provide source and destination']);
		$source = $args['emailprefix']."@".$args['domain'];
		$source = new Email($source);
		if (!$source->isValid())
			return new AliasAddError($this,['Source Email invalid']);
		$dest = new EmailCollection([$args['destination']]);
		if (!$dest->isValid())
			return new AliasAddError($this,['Destination Email invalid']);
		$mapper = new VirtualAliasMapper($this->db);
		$mapper->select(['source='.$source->toString()]);
		if ($mapper->fetchEntity())
			return new AliasAddError($this,['An Alias using this source already exists']);
		$domMapper = new VirtualDomainMapper($this->db);
		$domMapper->select(['name='.$args['domain']]);
		$domid = $domMapper->fetchEntity()->id;
		$alias = new VirtualAlias([
			'id' => null,
			'domain' => $domid,
			'source' => $source,
			'destination' => $dest
		]);
		$mapper->save($alias);
		return true;
		
	}
	
	public function addUser(array $args = []) {
		
		// Validate input
		if (empty($args['username']) || empty($args['password']))
			return new UserAddError($this,['Please provide username and password.']);
		$email = $args['username'].'@'.$args['domain'];
		$email = new Email($email);
		if (!$email->isValid())
			return new UserAddError($this,['Not a valid email address.']);
		
		// Check existing entry
		$mapper = new VirtualUserMapper($this->db);
		$mapper->select(["email=".$email->toString()]);
		if ($mapper->fetchEntity())
			return new UserAddError($this,[$email->toString().' already exists']);
		
		// Create entry
		$domainMapper = new VirtualDomainMapper($this->db);
		$domainMapper->select(['name='.$args['domain']]);
		$domain = $domainMapper->fetchEntity();
		$id = $domain->id;
		$password = new HashedPassword();
		$password->setPlainText($args['password']);
		$user = new VirtualUser([
			'id' => null,
			'domain' => $id,
			'email' => $email,
			'password' => $password
		]);
		$mapper->save($user);
		return true;
		
	}
	
	public function defaultAction(){
	
		return $this->showUsers();
	
	}
	
}

?>
