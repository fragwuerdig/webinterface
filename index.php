
<!DOCTYPE html>
<html>
	<head>
		<!--link rel="stylesheet" type="text/css" href="style/loginpage.css"> -->
	</head>
	<body>
		<?php	
			
			function requestHandler(array $post, array $get){
				
				$db = new MysqliPLayer();
				$sess = new Session();
				
				if (isset($sess->loginid)) {
					$controller = 'SessionController';
					if (isset($get['action']))
						$action = $get['action'];
					else
						$action = 'defaultAction';
				} else {
					$controller = 'LoginController';
					if (isset($get['action']))
						$action = $get['action'];
					else
						$action = 'defaultAction';
				}
				
				if (isset($get['success']))
					$action = 'defaultAction';
				
				$controller = new $controller($db,$sess);
				if (!method_exists($controller,$action))
					$action = 'defaultAction';
				
				return $controller->$action($post);
				
			}
			
			error_reporting(E_ALL);
			ini_set('display_errors',1);
			require_once("autoload.php");
			
			// Initial User
			/*$p = new HashedPassword();
			$p->setPlainText('test');
			$mapper = new VirtualUserMapper($db);
			$user= new VirtualUser(array(
				'id'=>null,
				'domain'=>1,
				'email'=>new Email('test@test.de'),
				'password'=>$p
				));
			$mapper->save($user);*/
			
			/*$controller = $_GET['controller'];
			$action = $_GET['action'];*/
			
			if (!empty($_POST)){
				$err = requestHandler($_POST,$_GET);
				if ($err) {
					if (is_bool($err)){
						header("HTTP/1.1 303 See Other");
						header("Location: index.php?controller=".$_GET['controller']."&action=".$_GET['action']."&success=1");
						die();
					}
					if (is_a($err,'Error')){
						$err->handle();
						die();
					}
				}
			}
			
			$err = requestHandler($_POST,$_GET);
			if ($err && is_a($err,'Error')){
				$err->handle();	
				die();
			}
			
		?>
	</body>
</html>
