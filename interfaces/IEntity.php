<?php

interface IEntity extends IRandomAccess{
	
	public function setId($id);
	public function getId();
	public function getFields();
	public function toArray();
	
}

?>
