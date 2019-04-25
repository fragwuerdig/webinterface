<?php

class VirtualDomainMapper extends Mapper {
	
	public function fetchEntity(){
		
		if ($row = $this->pLayer->fetch()) {
			$entity = new VirtualDomain([
				'id' => $row['id'],
				'name' => $row['name']
			]);
			return $entity;
		} else
			return false;
		
	}

}

?>
