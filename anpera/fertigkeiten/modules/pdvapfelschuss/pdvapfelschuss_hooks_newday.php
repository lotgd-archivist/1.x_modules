<?php
	global $session;
	require_once("lib/systemmail.php");
		$schuetze=get_module_setting("schuetze", "pdvapfelschuss");
		if ($schuetze != 0){
			systemmail($schuetze,"`@Pech gehabt!","`@Du hast so lange an dem Schießstand gewartet, dass sich "
				."die Menge schließlich aufzulösen begann. Der Troll zog die Konsequenz und schickte Dich "
				."fort, in der Hoffnung auf einen Schützen, der mehr Kundschaft anzieht. Dein Geld hast "
				."Du natürlich nicht zurückbekommen ...");
			set_module_setting("schuetze", 0, "pdvapfelschuss", $schuetze);
		}
	return $args;
?>
