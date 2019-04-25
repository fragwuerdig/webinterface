<?php

abstract class Mapper {
	
	protected $pLayer;
	
	public function __construct($pLayer) {
		
		$this->pLayer = $pLayer;
		
	}
	
	public function save(Entity $entity){
		
		$classname = get_class($this);
		$classname = str_replace("Mapper","",$classname);
		$data = $entity->toArray();
		if (isset($data['id'])){
			$id = $data['id'];
			$this->pLayer->update($classname,$data,["id=".$id]);
		}
		else
			$this->pLayer->insert($classname,$data);
				
	}
	
	public function select(array $cond = []) {
		
		$classname = get_class($this);
		$classname = str_replace("Mapper","",$classname);
		$this->pLayer->select($classname,$conds=$cond,$cols=['*']);
	
	}
	
	public function delete($id) {
		
		$classname = get_class($this);
		$classname = str_replace("Mapper","",$classname);
		$this->pLayer->delete($classname,['id='.$id]);
	
	}
	
	abstract public function fetchEntity();
	
}

?>
