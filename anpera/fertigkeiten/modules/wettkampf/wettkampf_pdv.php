<?php

function wettkampf_pdv_run_private($op=""){
	global $session;

	checkday();
	require_once("lib/commentary.php");
	
	modulehook("pdvanfang");
	
	$dauer1=get_module_setting("dauer1");
	$dauer0=get_module_setting("dauer0");
	$fest=get_module_setting("fest");
	$tage=get_module_setting("tage");
	$tage2=translate_inline("Tagen"); 
	if ($tage==1)$tage2=translate_inline("Tag");

	output("`@`b`cDer Platz der Völker`b`c`nDirekt neben den Gärten befindet sich ein großer, teils gepflasterter, teils als Wiese belassener Platz, der dem friedlichen Miteinander der großen Völker dieser Welt gewidmet ist. In regelmäßigen Abständen senden die Völker Delegationen von Schiedsrichtern, die einen Wettbewerb in der jeweiligen Landesdisziplin bewerten sollen. Alle %s Tage beginnt ein solches 'Fest der Völker', zu dem auch viele fahrende Händler anreisen, um ihre Stände auf dem Platz aufzubauen. Nach %s Tagen werden die Sieger der Wettbewerbe gekürt - wobei einzig zählt, wer den Gesamtsieg davongetragen hat. ", $dauer0, $dauer1);
		
	if ($fest=="0"){
		output("`@Direkt wenn Du den Platz betrittst, erblickst Du eine große Holztafel, auf der die Ergebnisse des letzten Festes und die Allzeitrekorde vermerkt sind. In der Mitte des Platzes steht eine große Marmorstatue der ehrenwerten Vermittlerin, der jungen Frau, ohne deren Bemühungen es niemals zum Bau dieses Platzes gekommen wäre.`n`n`bDas nächste Fest wird in `^%s`@ %s stattfinden.`b`n`n Bis dahin bleibt noch Zeit zum Üben bei den Ausbildern, die jedes Volk zur Förderung ihres jeweiligen Wettbewerbs beschäftigt. Alle Erlöse kommen einem mildtätigen Zweck zugute. Wenn Du möchtest, kannst Du Dich mit anderen Leuten unterhalten, die zum Trainieren hierher gekommen sind.`n`n", $tage, $tage2);
		modulehook("pdv-desc-keinfest");
		addnav("Die Statue");
		addnav("S?Zur Statue","runmodule.php?module=wettkampf&op1=statue");
		addnav("Die Ausbilder");
		addnav("1?Bogenschießen","runmodule.php?module=wettkampf&op1=aufruf&subop1=abogen&subop2=aus-bogen");
		addnav("2?Klettern","runmodule.php?module=wettkampf&op1=aufruf&subop1=aklettern&subop2=aus-klettern");
		addnav("3?Kochen","runmodule.php?module=wettkampf&op1=aufruf&subop1=akochen&subop2=aus-kochen");
		addnav("4?Musizieren","runmodule.php?module=wettkampf&op1=aufruf&subop1=amusik&subop2=aus-musik");
		addnav("5?Reiten","runmodule.php?module=wettkampf&op1=aufruf&subop1=areiten&subop2=aus-reiten");
		addnav("6?Schleichen","runmodule.php?module=wettkampf&op1=aufruf&subop1=aschleichen&subop2=aus-schleichen");
		addnav("7?Schwimmen","runmodule.php?module=wettkampf&op1=aufruf&subop1=aschwimmen&subop2=aus-schwimmen");
	}else if ($fest=="1"){
		$tage2=translate_inline("Tage"); 
		if ($tage==1)$tage2=translate_inline("Tag");
		
		output("`@Direkt wenn Du den Platz betrittst, erblickst Du eine große Holztafel, auf der die bisherigen Ergebnisse des laufenden Festes und die Allzeitrekorde vermerkt sind. In der Mitte des Platzes steht eine große Marmorstatue der ehrenwerten Vermittlerin, der jungen Frau, ohne deren Bemühungen es niemals zum Bau dieses Platzes gekommen wäre.`n`n`bDerzeit findet hier ein Fest der Völker statt! Es dauert noch `^%s`@ %s.`b`n`n Da die Ausbilder während des Festes Mitglieder der Jury sind, haben sie keine Zeit Dich zu unterrichten. Wenn Du möchtest, kannst Du ein wenig über den Markt schlendern, an den Wettbewerben teilnehmen oder Dich mit anderen Festbesuchern unterhalten.`n", $tage, $tage2);
		modulehook("pdv-desc");
		
		output("`n");
		addnav("Die Statue");
		addnav("Zur Statue","runmodule.php?module=wettkampf&op1=statue");
		addnav("Die Wettbewerbe");
		addnav("1?Bogenschießen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wbogen&subop2=bogen");
		addnav("2?Klettern","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=klettern");
		addnav("3?Kochen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wkochen&subop2=kochen");
		addnav("4?Musizieren","runmodule.php?module=wettkampf&op1=aufruf&subop1=wmusik&subop2=musik");
		addnav("5?Reiten","runmodule.php?module=wettkampf&op1=aufruf&subop1=wreiten&subop2=reiten");
		addnav("6?Schleichen und Verstecken","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschleichen&subop2=schleichen");
		addnav("7?Schwimmen und Tauchen","runmodule.php?module=wettkampf&op1=aufruf&subop1=wschwimmen&subop2=schwimmen");
		addnav("Marktstände");
		$staende = modulehook("pdvstände");
		foreach($staende as $module=>$values) {
			if ($values['appear']==1) addnav($values['name'], "runmodule.php?module=$module");
		}
	}
	addnav("Sonstiges");
	addnav("9?Meine Werte","runmodule.php?module=wettkampf&op1=werte");
	modulehook("pdvnavsonstiges");
	if ($session['user']['superuser'] & SU_EDIT_COMMENTS){
	    addnav("Superuser");
		addnav(",?Moderation","moderate.php");
	}
	addnav("Zurück");
	addnav("Z?Zum Gemeindeplatz","village.php");

	addcommentary();
	viewcommentary("wettkampf","Mit anderen unterhalten:",25,"sagt fröhlich");
	
page_footer();
}
?>