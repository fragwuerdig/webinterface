
<table>
	<tr>
		<th>ID</th>
		<th>Email</th>
		<th>Password</th>
		<th></th>
	</tr>
	<?php
	foreach ($_['users'] as $key => $value){
		echo "<tr>";
		echo "<td>".$key."</td>";
		echo "<td>".$value."</td>";
		echo "<td>
				<form action=\"index.php?controller=SessionController&action=setUserPassword\" method=\"post\">
					<input type=\"hidden\" name=\"id\" value=\"".$key."\">
					<input type=\"text\" name=\"password\">
					<input type=\"submit\" value=\"Set\">
				</form>
			</td>";
		echo "<td>
				<form action=\"index.php?controller=SessionController&action=deleteUser\" method=\"post\">
					<input type=\"hidden\" name=\"id\" value=\"".$key."\">
					<input type=\"submit\" value=\"Delete\">
				</form>
			  </td>";
		echo "</tr>";
	}
	?>
</table>
<form action="index.php?controller=SessionController&action=addUser" method="post">
	<input name="username" type="text">
	@
	<select name="domain" size="1">
		<?php
			foreach($_['domains'] as $key => $domain)
				echo "<option>".$_['domains'][$key]."</option>";
		?>
	</select>
	<input name="password" type="text" placeholder="Password">
	<input type="submit" value="Add">
</form> 
<?php

	if (!empty($_['message'])){
		foreach($_['message'] as $message)
			echo $message;
	}

?>
