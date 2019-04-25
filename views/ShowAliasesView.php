<?php

class ShowAliasesView extends View {
	
	public function display(){
		
		$newparam = [];
		foreach($this->param['domains'] as $domain)
			array_push($newparam,$domain->name);
		$this->param['domains'] = $newparam;
		
		$newparam = [];
		foreach($this->param['aliases'] as $alias){
			array_push($newparam,array(
				'source' => $alias->source->toString(),
				'destination' => $alias->destination->toStringArray()
			));
		}
		$this->param['aliases'] = $newparam;
		
		parent::display();
		
	}
	
}

?>
