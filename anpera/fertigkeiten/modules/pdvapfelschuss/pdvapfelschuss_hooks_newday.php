<?php
	global $session;
	require_once("lib/systemmail.php");
		$schuetze=get_module_setting("schuetze", "pdvapfelschuss");
		if ($schuetze != 0){
			systemmail($schuetze,"`@Pech gehabt!","`@Du hast so lange an dem Schie�stand gewartet, dass sich "
				."die Menge schlie�lich aufzul�sen begann. Der Troll zog die Konsequenz und schickte Dich "
				."fort, in der Hoffnung auf einen Sch�tzen, der mehr Kundschaft anzieht. Dein Geld hast "
				."Du nat�rlich nicht zur�ckbekommen ...");
			set_module_setting("schuetze", 0, "pdvapfelschuss", $schuetze);
		}
	return $args;
?>
