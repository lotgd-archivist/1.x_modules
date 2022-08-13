<?php

function wettkampf_wklettern_wklettern1_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der V�lker");
	require_once("lib/fert.php");
		$bestklettern0=get_module_pref("bestklettern0", "wettkampf");
		$wklettern0=get_module_pref("wklettern0", "wettkampf");
		$kommentar="";
		
		if ($wklettern0==0) $kommentar=translate_inline("`@Dann sagt er `#'Das passiert wohl jedem mal ...'`@, aber es ist eine Menge kaum versteckter Hohn in seiner Stimme.");
		else if ($wklettern0<50 && $wklettern0!=0) $kommentar=translate_inline("`@Dann lacht er Dich aus: `#'Das ist ja wohl l�cherlich ... Jedes Kind k�me tiefer!' `@Die umstehenden Zuschauer stimmen in sein Gel�chter ein.");
		else if ($wklettern0>50 && $wklettern0 <= 150) $kommentar=translate_inline("`@Du h�rst einige Buhrufe.");
		
		else if ($wklettern0>300 && $wklettern0 <= 500) $kommentar=translate_inline("`@Du erh�ltst vereinzelten Applaus.");
		else if ($wklettern0>500 && $wklettern0 <= 700) $kommentar=translate_inline("`@Du erh�ltst Applaus.");
		else if ($wklettern0>700 && $wklettern0 <= 900) $kommentar=translate_inline("`@Du erh�ltst Applaus und jemand klopft Dir anerkennend auf die Schulter.");
		else if ($wklettern0>900 && $wklettern0 <= 1100) $kommentar=translate_inline("`@Du erh�ltst gro�en Applaus.");
		else if ($wklettern0>1100 && $wklettern0 <= 1300) $kommentar=translate_inline("`@Du erh�ltst gro�en Applaus und musst ein Autogramm geben.");
		else if ($wklettern0>1300 && $wklettern0 <= 1500) $kommentar=translate_inline("`@Du erh�ltst riesigen Applaus und musst mehrere Autogramme geben.");
		else if ($wklettern0>1500 && $wklettern0 <= 1600) $kommentar=translate_inline("`@Du erh�ltst riesigen Applaus und musst viele Autogramme geben.");
		else if ($wklettern0>1600){
			$kommentar=translate_inline("`@Du erh�ltst gigantischen Applaus und musst viele Autogramme geben. Regon selbst klopft Dir anerkennend auf die Schulter: `#'Eine gro�e Leistung, wahrhaft gro�.'");
			$comment=translate_inline("/me `@kehrt gefeiert vom Kletterwettbewerb zur�ck und muss viele Autogramme geben. Das war eine absolute Spitzenleistung!");
			injectcommentary(wettkampf, "", $comment, $schema=false);		
		}
		
		output("`@Regon zieht Dich mit der Seilwinde hoch und vermerkt Dein Endergebnis auf einer gro�en Tafel: %s.`n`n%s", ($wklettern0==0?"`\$Disqualifiziert`@":"`^$wklettern0`@ Meter"), $kommentar);
		
		//Folgende Werte werden gespeichert, damit sich die Sortierung der Bestenlisten nicht �ndert, wenn
		//jemand bspw. einen Level aufsteigt:
		set_module_pref("wkletternlevel", $session[user][level], "wettkampf");
		set_module_pref("wkletterndk", $session[user][dragonkills], "wettkampf");
		set_module_pref("wkletternfw", $klettern, "wettkampf");
	
		if ($wklettern0>$bestklettern0){
			set_module_pref("bestklettern0", $wklettern0, "wettkampf"); 
			set_module_pref("bestkletternlevel", $session[user][level], "wettkampf");
			set_module_pref("bestkletterndk", $session[user][dragonkills], "wettkampf");
			set_module_pref("bestkletternfw", $klettern, "wettkampf");
		}
		addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=klettern");
	page_footer();
}
?>