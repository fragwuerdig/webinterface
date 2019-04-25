
<form action="index.php?controller=LoginController&action=doLogin" method="post">
	<input placeholder="Email" type="text" name="username"><br>
	<input placeholder="Password" type="password" name="password"><br>
	<input type="submit" value="Login">
</form>
<?php
	foreach($_ as $message)
		echo "<p>".$message."</p>";
?>
