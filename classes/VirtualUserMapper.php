<?php

class VirtualUserMapper extends Mapper {
	
	public function fetchEntity(){
		
		if ($row = $this->pLayer->fetch()) {
			$p = new HashedPassword();
			$hash = $row['password'];
			$p->setHash($hash);
			$entity = new VirtualUser([
				'id' => $row['id'],
				'domain' => $row['domain'],	//Using Proxies may be better here.
				'email' => new Email($row['email']),
				'password' => $p
			]);
			return $entity;
		} else
			return false;
		
	}

}

?>
