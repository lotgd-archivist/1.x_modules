<?php

function pdvdiebstahl_main_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Taschendiebstahl");

	checkday();
	$diebid=get_module_setting("diebid");
	if ($diebid==$session[user][acctid]){
	output("`@Du bist noch unterwegs. Habe Geduld ...");
	addnav("Zurück","runmodule.php?module=wettkampf&op1=");
	}else{
		$geklaut=get_module_pref("geklaut");
		if ($geklaut==1){
			output("`@Ein weiterer Versuch wäre zu riskant. Warte bis morgen.");
			addnav("Zurück","runmodule.php?module=wettkampf&op1=");
		}else{
			$fest=get_module_setting("fest", "wettkampf");
			$immun=get_module_pref("diebstahlsimmun", "pdvdiebstahl");
			$text="";
			$text2="";
			if ($immun==1) $text2=translate_inline("`4`nAchtung: Das wird Dich Deine Immunität gegen Diebstähle kosten.`@");
			if ($fest==0) $text=translate_inline("Bedenke dabei, dass im Moment kein Fest stattfindet und Du deshalb nicht den Schutz der Menge genießt.");
			output("`@Möchtest Du auf Diebestour gehen? %s %s", $text, $text2);
			output("`@`n`n<a href='runmodule.php?module=pdvdiebstahl&op1=diebstahl'>Ja, her mit dem Geld!</a>", true);
			output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>Lieber nicht ...</a>", true);
			addnav("","runmodule.php?module=pdvdiebstahl&op1=diebstahl");
			addnav("","runmodule.php?module=wettkampf&op1=");
			addnav("Ja!","runmodule.php?module=pdvdiebstahl&op1=diebstahl");
			addnav("Lieber nicht.","runmodule.php?module=wettkampf&op1=");
		}
	}
	page_footer();
}
?>