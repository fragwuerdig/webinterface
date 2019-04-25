
<table>
	<tr>
		<th>Emails arriving at address...</th>
		<th>Will be forwarded to listed addresses</th>
		<th>Add destination to list</th>
	</tr>
	<?php
	foreach ($_['aliases'] as $alias){
		echo "<tr>";
		echo "<td>".$alias['source']."</td>";
		echo "<td>";
			echo "<form action=\"index.php?controller=SessionController&action=deleteAlias\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"source\" value=\"".$alias['source']."\">";
				echo "<select name=\"destination\" size=\"1\">";
					foreach($alias['destination'] as $dest)
						echo "<option>".$dest."</option>";
				echo "</select>";
				echo "<input type=\"submit\" value=\"Delete\">";
			echo "</form>";
		echo "</td>";
		echo "<td>";
			echo "<form action=\"index.php?controller=SessionController&action=addAliasToList\" method=\"post\">";
				echo "<input name=\"destination\" type=\"text\">";
				echo "<input type=\"hidden\" name=\"source\" value=\"".$alias['source']."\">";
				echo "<input type=\"submit\" value=\"Add\">";
			echo "</form>";
		echo "</td>";
		echo "</tr>";
	}
	?>
</table>
<form action="index.php?controller=SessionController&action=addAlias" method="post">
	Forward
	<input type="text" name="emailprefix">
	@
	<select name="domain" size="1">
		<?php
			foreach($_['domains'] as $domain)
				echo "<option>".$domain."</option>";
		?>
	</select>
	to
	<input type="text" name="destination">
	<input type="submit" value="OK">
</form>
<p>
	<?php if(!empty($_['message']))
			echo $_['message'][0];
	?>
</p>
	
