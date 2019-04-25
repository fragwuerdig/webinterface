
<?php

function autoload($classname) {

	$DS = DIRECTORY_SEPARATOR;
	$classpaths = array(
		__DIR__.$DS,
		__DIR__.$DS.'controllers'.$DS,
		__DIR__.$DS.'classes'.$DS,
		__DIR__.$DS.'interfaces'.$DS,
		__DIR__.$DS.'views'.$DS
	);
	foreach ($classpaths as $path){
		$file = $path.$classname.".php";
		if (file_exists($file)){
			require_once($file);
			return true;
		}
	}
	return false;
	
}

spl_autoload_register('autoload');

?>
