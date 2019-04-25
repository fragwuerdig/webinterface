<?php

interface IPLayer {
	
	public function insert($table,array $data);
	public function select($table,array $conds,array $cols);
	//public function delete($locator,$rowCond);
	public function update($table,array $data, array $conds);
	public function delete($table,array $conds);
	public function fetch();
	
}

?>
