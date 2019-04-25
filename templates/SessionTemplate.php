
<div class="sessionwrapper">
	<div id="menu">
		<ul>
			<?php
				if (isset($_['menu'])){
					foreach ($_['menu'] as $key => $uri){
						if (isset($uri))
							echo "<li><a href=\"".$uri."\">".$key."</a></li>";
						else
							echo "<li>".$key."</li>";
					}
				}
			?>
		</ul>
	</div>
	<div id="content">
		<?php isset($_['content']) ? $_['content']->display() : false ?>
	</div>
</div>
