<?php
		if ($session['user']['superuser'] & SU_EDIT_COMMENTS) {
			addnav("Validations");
			addnav("Validate Avatars","runmodule.php?module=avatar&op=validate");
		}
?>
