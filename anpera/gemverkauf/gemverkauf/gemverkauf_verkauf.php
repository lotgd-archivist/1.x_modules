<?php

function gemverkauf_verkauf_run_private($args=false){
	global $session;
		page_header("Der Rubel rollt");
			
			$menge=$_GET[menge];
			//Eine Zeile z.T. �bernommen aus der 0.97er Erweiterung (Autor mir nicht bekannt)
			$costs=array(1=>3500-10*$menge, 7300-20*$menge, 11000-32*$menge, 1180-4*$menge);	
		
			output("`5Sie nimmt Deinen Edelstein entgegen und gibt Dir daf�r `^%s`5 Goldst�cke", $costs[4]);
			debuglog("Zigeunerin: Einen Edelstein f�r ".$costs[4]." Goldst�cke verkauft.");
			
			$session['user']['gems']--;
			$session['user']['gold']+=$costs[4];
			
			debuglog("Erhalten: ".$costs[4]." Gold bei gemverkauf gegen 1 Edelstein.");
			
			$usermenge=get_module_pref("menge");
			set_module_pref("menge", $usermenge+1);
			$menge=get_module_setting("menge");
			set_module_setting("menge", $menge+1);
			
			addnav("Zur�ck", "gypsy.php");
		page_footer();
}
?>
