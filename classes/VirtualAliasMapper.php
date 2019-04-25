<?php

class VirtualAliasMapper extends Mapper {
	
	public function fetchEntity(){
		
		if ($row = $this->pLayer->fetch()) {
			$entity = new VirtualAlias([
				'id' => $row['id'],
				'domain' => $row['domain'],	//Using Proxies may be better here.
				'source' => new Email($row['source']),
				'destination' => new EmailCollection(explode(",",$row['destination']))
			]);
			return $entity;
		} else
			return false;
		
	}
	
}

?>
