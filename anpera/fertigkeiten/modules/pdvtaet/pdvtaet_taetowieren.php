<?php

function pdvtaet_taetowieren_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Der T�towierer");
	
		if ($session['user']['gems'] < 15){
			output("`@Daf�r hast Du nicht gen�gend Edelsteine dabei!");
			addnav("Zur�ck");
			addnav("Zum Platz der V�lker", "runmodule.php?module=wettkampf");
		}else{			
			$subop=$_GET[subop];
			$aktion=$_GET[aktion];
			$motiv=$_GET[motiv];
				
			if ($aktion == "verify"){
				$motiv=$_POST[motiv];
				output("`@Dein Wunschmotiv: %s`@.`n`nIst das so in Ordnung?", $motiv);
				
				addnav("Ja", "runmodule.php?module=pdvtaet&op1=taetowieren&subop=".$subop."&aktion=taet&motiv=".rawurlencode($motiv)."");
				addnav("Nein", "runmodule.php?module=pdvtaet&op1=taetowieren&subop=".$subop."&aktion=auswahl&motiv=".rawurlencode($motiv)."");
				addnav("Weiter", "runmodule.php?module=wettkampf");
			}else if ($aktion == "taet"){
				output("`@Du wirst von Phral hinter den Vorhang gef�hrt und tauchst ein in die Welt nicht enden "
					."wollender Schmerzen. Als er sein Werk beendet hat, bist Du v�llig ersch�pft. Aber das ist "
					."erst der Anfang, denn nun beginnt die lange Phase der Heilung von den dreckigen Nadelstichen ...");
				$koerper=createarray(get_module_pref("koerper"));
				$koerper[$subop]['motiv']=$motiv;
				set_module_pref("koerper", createstring($koerper));
				set_module_pref("heilung", e_rand(5,15));
				$session['user']['gems']-=15;
				
				$session['user']['hitpoints']=1;
				addnav("Weiter", "runmodule.php?module=wettkampf");
			}else if ($aktion == "auswahl"){
				output("`@Gib Dein Wunschmotiv ein (Maximal 50 Zeichen / Farbtags sind erlaubt!):");
				output("
					<form method='post' action='runmodule.php?module=pdvtaet&op1=taetowieren&subop=".$subop."&aktion=verify'>
					<input type=text size=50 maxlength=50 name=motiv>`n`n
					<input type='submit'>
					</form>",true);
				addnav("", "runmodule.php?module=pdvtaet&op1=taetowieren&subop=".$subop."&aktion=verify");
				output("`n`\$Achtung: Es gelten dieselben Regeln wie bei der Namenswahl! Wer dagegen verst��t, "
					."kann damit rechnen, dass alle seine T�towierungen auf einen Schlag gel�scht werden.`n`nWir "
					."l�schen keine einzelnen T�towierungen und f�hren keine Umbenennungen durch.");
				addnav("Zur�ck");
				addnav("Zum Platz der V�lker", "runmodule.php?module=wettkampf");
			}
		}
	page_footer();
}
?>