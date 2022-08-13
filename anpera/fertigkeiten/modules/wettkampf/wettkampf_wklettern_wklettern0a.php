<?php

function wettkampf_wklettern_wklettern0a_run_private($op, $subop=false){
	global $session;
	page_header("Der Platz der Völker");
	require_once("lib/fert.php");
		output("`@Du wagst es, tiefer zu klettern ... `n`@");
		
		$klettern=get_fertigkeit(klettern);
		$wklettern0=get_module_pref("wklettern0", "wettkampf");
		if ($wklettern0 == 10000) $wklettern0=0;
		$waspassiert=0;
		$zufall=e_rand(1,100);
		$mod=65;
		$ereignis=0;
		
		if ($zufall==1){
			$text1=translate_inline("Du entdeckst ein gutplatziertes Steigeisen, das nach dem letzten Teilnehmer wohl nicht entfernt worden ist. ");
			$text2=translate_inline("`\$Aber selbst das hat nichts genützt ...`@");
			$ereignis=30; 
			$waspassiert=1;
		}else if ($zufall==20){
			$text1=translate_inline("Aufgrund des guten Vorankommens fühlst Du Dich euphorisch und bist besonders aufmerksam für gute Stellen. ");
			$text2=translate_inline("`\$Etwas zu euphorisch ...`@");
			$ereignis=20;
			$waspassiert=1;  
		}else if ($zufall==40){
			$text1=translate_inline("Aus einer Wand ragt die Wurzel eines Baumes ... in dieser Tiefe? Aber was soll's, Hauptsache, sie nützt Dir. ");
			$text2=translate_inline("`\$Nur viel hat sie Dir diesmal nicht genützt ...`@");
			$ereignis=10; 
			$waspassiert=1; 
		}else if ($zufall==60){
			$text1=translate_inline("`\$Ein wenig staubiges Geröll fällt Dir auf den Kopf und nimmt Dir für einen Moment die Sicht.`@ ");
			$text2=translate_inline("`@Aber das macht jemandem wie Dir nichts aus, Du schüttelst Dich und kletterst weiter ...");
			$ereignis=-10; 
			$waspassiert=1; 
		}else if ($zufall==80){
			$text1=translate_inline("`\$Die Stelle, an die Du gerade gegriffen hast, erweist sich als ausgesprochen rutschig!`@ ");
			$text2=translate_inline("`@Aber das hast Du stundenlang geübt, einfach woanders hingreifen ...");
			$ereignis=-20; 
			$waspassiert=1;
		}else if ($zufall==100){
			$text1=translate_inline("`\$Plötzlich spürst Du einen Stich in Deiner Hand und ziehst sie reflexartig weg!`@ ");
			$text2=translate_inline("`@Aber nur, um woanders noch besseren Halt zu finden ...");
			$ereignis=-30;
			$waspassiert=1;
		}
		
		if ($wklettern0>50 && $wklettern0 <= 150) $mod=40+$ereignis;
		if ($wklettern0>150 && $wklettern0 <= 300) $mod=30+$ereignis;
		if ($wklettern0>300 && $wklettern0 <= 500) $mod=20+$ereignis;
		if ($wklettern0>500 && $wklettern0 <= 700) $mod=10+$ereignis;
		if ($wklettern0>700 && $wklettern0 <= 900) $mod=$ereignis;
		if ($wklettern0>900 && $wklettern0 <= 1100) $mod=-15+$ereignis;
		if ($wklettern0>1100 && $wklettern0 <= 1300) $mod=-25+$ereignis;
		if ($wklettern0>1300 && $wklettern0 <= 1500) $mod=-35+$ereignis;
		if ($wklettern0>1500 && $wklettern0 <= 1600) $mod=-50+$ereignis;
		if ($wklettern0>1600) $mod=-70+$ereignis;
	
		//Probe (Zufall wird separat ermittelt, daher 0, 0)
		$probe=probe($klettern, $mod, 0, 0, true);
		$t1=$probe[wert];
	
		//Mißlungen mit Ereignis
		if ($t1<=0 && $zufall <50 && $waspassiert==1) output("%s%s `\$Du rutschtst ab und baumelst nun mitten im Schacht an der Leine.", $text1, $text2);
		else if ($t1<=0 && $zufall >50 && $waspassiert==1) output("%s `\$Das verunsichert Dich dermaßen, dass Du abrutscht und nun mitten im Schacht an der Leine baumelst.", $text1);
		
		//Mißlungen ohne Ereignis
		else if ($t1<=0 && $waspassiert==0) output("Du rutscht ab und baumelst nun mitten im Schacht an der Leine.");
		
		//Gelungen mit Ereignis
		else if ($t1>0 && $zufall <50 && $waspassiert==1) output("`@%s Mit diesem Glück kommst Du `^%s`@ Meter tiefer, wie Du an den Markierungen im Fels erkennen kannst.", $text1, $t1);
		else if ($t1>0 && $zufall >50 && $waspassiert==1) output("`@%s%s `@Du schaust auf die Markierungen im Fels und erkennst, dass Du `^%s`@ Meter tiefer gekommen bist.", $text1, $text2, $t1);
		
		//Gelungen ohne Ereignis
		else if ($t1>0 && $waspassiert==0) output("`@Als Du nach einigen Minuten auf die Markierungen im Fels siehst, stellst Du fest, dass Du `^%s`@ Meter tiefergekommen bist.", $t1);
		
		$wklettern0=$wklettern0+$t1;
		set_module_pref("wklettern0", $wklettern0, "wettkampf");
			
		//Könnte weitergehen
		if ($t1>0){
			output("`n`nBis jetzt hast Du eine Tiefe von `^%s`@ Metern erreicht. Du schaust hinab ", $wklettern0);
			if ($wklettern0<=50) output("und erkennst noch immer viele perfekte Stellen zum Weiterklettern.");
			else if ($wklettern0>50 && $wklettern0 <= 150) output("und erkennst noch immer viele sehr gute Stellen zum Weiterklettern.");
			else if ($wklettern0>150 && $wklettern0 <= 300) output("und erkennst noch immer viele gute Stellen zum Weiterklettern.");
			else if ($wklettern0>300 && $wklettern0 <= 500) output("und erkennst noch immer ein paar gute Stellen zum Weiterklettern.");
			else if ($wklettern0>500 && $wklettern0 <= 700) output("und erkennst noch immer ein paar brauchbare Stellen zum Weiterklettern.");
			else if ($wklettern0>700 && $wklettern0 <= 900) output("und erkennst eine brauchbare Stelle zum Weiterklettern.");
			else if ($wklettern0>900 && $wklettern0 <= 1100) output("und erkennst nur eine kaum brauchbare Stelle zum Weiterklettern. Es wird dunkler.");
			else if ($wklettern0>1100 && $wklettern0 <= 1300) output("und erkennst nur eine kaum brauchbare Stelle zum Weiterklettern. Es ist dunkel geworden.");
			else if ($wklettern0>1300 && $wklettern0 <= 1500) output("und erkennst nur eine ganz vielleicht brauchbare Stelle zum Weiterklettern. Es ist sehr dunkel geworden.");
			else if ($wklettern0>1500 && $wklettern0 <= 1600) output("und erkennst nichts. Es ist stockduster.");
			else if ($wklettern0>1600) output("und erkennst nichts. Es ist stockduster und Du hörst ein seltsames Geifern aus der Tiefe, das Dich sehr verunsichert.");
		
			output("`n`n`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern0a'>Ich muss tiefer, viiiel tiefer!</a>", true);
			output("`n`n`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern1'>Puh ... mehr riskiere ich nicht. Das muss reichen.</a>", true);
	
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern0a");
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern1");
			addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern0a");
			addnav("Aufhören","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern1");
		}
	
		//Schluss
		if ($t1<=0){
			set_module_pref("wklettern0", 0, "wettkampf");
			output("`n`n`\$Du wirst leider disqualifiziert!`@");
			output("`n`n`@<a href='runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern1'>Weiter.</a>", true);
			addnav("","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern1");
			addnav("Weiter","runmodule.php?module=wettkampf&op1=aufruf&subop1=wklettern&subop2=wklettern1");
		}
	page_footer();
}
?>