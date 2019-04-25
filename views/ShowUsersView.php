<?php

class ShowUsersView extends View {
	
	public function display(){
		
		$newparam = [];
		foreach($this->param['users'] as $key => $user)
			$newparam += array($user->id => $user->email->toString());
		$this->param['users'] = $newparam;
		
		$newparam = [];
		foreach($this->param['domains'] as $key => $domain)
			$newparam += array($key => $domain->name);
		$this->param['domains'] = $newparam;
		
		parent::display();
		
	}
	
}

?>
