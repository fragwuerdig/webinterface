
<?php

class DefaultController extends Controller {
	
	public function showDefaultPage(){
		
		$view = new DefaultView();
		$view->display();
		
	}
	
}

?>
