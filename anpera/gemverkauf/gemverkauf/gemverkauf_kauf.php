<?php

function gemverkauf_kauf_run_private($args=false){
	global $session;
		page_header("Der Rubel rollt");
			$menge=$_GET[menge];
			
			//Eine Zeile z.T. übernommen aus der 0.97er Erweiterung (Autor mir nicht bekannt)
			$costs=array(1=>3500-10*$menge, 7300-20*$menge, 11000-32*$menge, 1180-4*$menge);	
		
			$subop=$_GET[subop];
			if ($session['user']['gold'] < $costs[$subop]) output("`5Sie schaut Dich empört an: `!'Du hast ja gar nicht "
				."genug Gold dabei! So nicht!'");
			else{
				output("`5Sie gibt Dir im Tausch für `^%s`5 Goldstücke %s.", $costs[$subop], ($subop==1?"einen`5 Edelstein":"$subop`5 Edelsteine"));
				debuglog("Zigeunerin: ".$subop." Edelstein(e) für ".$costs[$subop]." Goldstücke gekauft.");
				$session['user']['gems']+=$subop;
				$session['user']['gold']-=$costs[$subop];
				debuglog("Verlust: ".$costs[$subop]." Gold für Kauf ".$subop." Edelsteine.");
				$menge=get_module_setting("menge");
				set_module_setting("menge", $menge-$subop);
			}
			addnav("Zurück", "gypsy.php");
		page_footer();
}
?>
