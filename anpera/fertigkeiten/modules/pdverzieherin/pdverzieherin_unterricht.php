<?php

function pdverzieherin_unterricht_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Die Erzieherin");
	$gems=$_GET[subop];
		$teilnahme=get_module_pref("teilnahme", "pdverzieherin");
		
		if ($gems > $session['user']['gems']) output("`@Du willst gerade einwilligen, als Dir einf�llt, "
			."dass Du nicht gen�gend Edelsteine dabeihast ...");
		else if ($teilnahme == 3) output("`@Du willst gerade einwilligen, als Dich ein pl�tzlicher "
			."Kopfschmerz �berw�ltigt. F�r heute hast Du genug gelernt, das musst Du jetzt erstmal "
			."verarbeiten ...");
		else{
			output("`@Du bezahlst den Betrag im voraus - denn Du willst ja h�flich sein - und "
				."wirst von der Dame auch nicht entt�uscht. Nach einem ausgiebigen Unterricht "
				."in allen Formen der H�flichkeit und allgemeinen Attraktivit�tssteigerung "
				."d�rfte sich Deine Attraktivit�t um einiges erh�ht haben - sofern Du alles "
				."beh�ltst, was sie Dir vermittelt hat ... `n`n");
			
			$session['user']['charm']++;
			$session['user']['gems']-=$gems;
			$teilnahme=get_module_pref("teilnahme", "pdverzieherin");
			$teilnahme_neu=$teilnahme + 1;
			set_module_pref("teilnahme", $teilnahme_neu, "pdverzieherin");
			
			if ($session['user']['charm'] < 150) output("Die Dame entl�sst Dich mit dem Hinweis, "
				."dass Du noch �fter herkommen solltest.");
			else if ($session['user']['charm'] == 150) output("Die Dame entl�sst Dich mit dem Hinweis, "
				."dass Du nun schon so gut erzogen bist, dass die F�rderung beim n�chsten Mal teurer, daf�r "
				."aber auch intensiver sein wird.");
			else if ($session['user']['charm'] > 150 && $session['user']['charm'] < 250) output("Die Dame entl�sst Dich mit dem Hinweis, "
				."dass Du zwar gro�e Fortschritte machst, sie Dir aber noch einiges mehr vermitteln kann.");
			else if ($session['user']['charm'] == 250) output("Die Dame entl�sst Dich mit dem Hinweis, "
				."dass sie alles getan hat, was in ihrer Macht steht. Nun musst Du Deinen Weg zur "
				."maximalen Attraktivit�t alleine fortsetzen. Du bedankst Dich h�flich f�r alles und "
				."empiehlst Dich dann.");			
		}
		addnav("Zur�ck", "runmodule.php?module=wettkampf");
	page_footer();
}
?>