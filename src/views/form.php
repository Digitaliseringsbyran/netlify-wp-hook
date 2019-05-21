<form method="post" action="options.php">
	<?php
		settings_fields( 'netlify' );
		do_settings_sections( 'netlify' );
		submit_button();
	?> 
</form>