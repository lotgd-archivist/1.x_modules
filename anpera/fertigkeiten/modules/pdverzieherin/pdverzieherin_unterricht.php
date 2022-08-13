<?php

function pdverzieherin_unterricht_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Die Erzieherin");
	$gems=$_GET[subop];
		$teilnahme=get_module_pref("teilnahme", "pdverzieherin");
		
		if ($gems > $session['user']['gems']) output("`@Du willst gerade einwilligen, als Dir einfällt, "
			."dass Du nicht genügend Edelsteine dabeihast ...");
		else if ($teilnahme == 3) output("`@Du willst gerade einwilligen, als Dich ein plötzlicher "
			."Kopfschmerz überwältigt. Für heute hast Du genug gelernt, das musst Du jetzt erstmal "
			."verarbeiten ...");
		else{
			output("`@Du bezahlst den Betrag im voraus - denn Du willst ja höflich sein - und "
				."wirst von der Dame auch nicht enttäuscht. Nach einem ausgiebigen Unterricht "
				."in allen Formen der Höflichkeit und allgemeinen Attraktivitätssteigerung "
				."dürfte sich Deine Attraktivität um einiges erhöht haben - sofern Du alles "
				."behältst, was sie Dir vermittelt hat ... `n`n");
			
			$session['user']['charm']++;
			$session['user']['gems']-=$gems;
			$teilnahme=get_module_pref("teilnahme", "pdverzieherin");
			$teilnahme_neu=$teilnahme + 1;
			set_module_pref("teilnahme", $teilnahme_neu, "pdverzieherin");
			
			if ($session['user']['charm'] < 150) output("Die Dame entlässt Dich mit dem Hinweis, "
				."dass Du noch öfter herkommen solltest.");
			else if ($session['user']['charm'] == 150) output("Die Dame entlässt Dich mit dem Hinweis, "
				."dass Du nun schon so gut erzogen bist, dass die Förderung beim nächsten Mal teurer, dafür "
				."aber auch intensiver sein wird.");
			else if ($session['user']['charm'] > 150 && $session['user']['charm'] < 250) output("Die Dame entlässt Dich mit dem Hinweis, "
				."dass Du zwar große Fortschritte machst, sie Dir aber noch einiges mehr vermitteln kann.");
			else if ($session['user']['charm'] == 250) output("Die Dame entlässt Dich mit dem Hinweis, "
				."dass sie alles getan hat, was in ihrer Macht steht. Nun musst Du Deinen Weg zur "
				."maximalen Attraktivität alleine fortsetzen. Du bedankst Dich höflich für alles und "
				."empiehlst Dich dann.");			
		}
		addnav("Zurück", "runmodule.php?module=wettkampf");
	page_footer();
}
?>