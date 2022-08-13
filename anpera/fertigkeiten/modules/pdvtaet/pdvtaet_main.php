<?php

function pdvtaet_main_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Der Tätowierer");
	
		checkday();
		$heilung=get_module_pref("heilung", "pdvtaet");
		if ($heilung > 0) output("`@Du kommst an dem Zelt des Tätowierers vorbei und ertappst Dich dabei, "
			."unbewusst an Deiner verheilenden Tätowierung zu reiben. Sie juckt noch recht stark - komm "
			."wieder, wenn sie ganz verheilt ist.");
		else{
			output("`@`cDer Tätowierer`c`nAls Du das Zelt betrittst, ist niemand zu entdecken. Du beginnst "
				."Dir einige der ausgehängten Motive näher anzuschauen und willst schon gerade wieder gehen, "
				."als Du einen lauten Schrei vernimmst. Vorsichtig lugst Du hinter einen Vorhang und fährst "
				."erschrocken in Dich zusammen, als er zur Seite gerissen wird - noch bevor Du einen Blick "
				."auf das Dahinter erhaschen kannst.`nVor Dir steht ein stämmiger Troll, der am ganzen Körper "
				."tätowiert ist: `#'Ah, Kundschaft ... mein Name ist Phral. Was darf's denn sein?' `@Du musst nicht lange überlegen - "
				."und wenn doch, dann bist Du hier wohl gerade falsch ...`n`nAuf einem Schild steht:`n"
				."Tätowierung nach Wunsch: `^15`@ Edelsteine.`n"
				."Entfernung mit Zauberkraft: `^7`@ Edelsteine. (Nur bei bester Gesundheit!)`n`n");
			$koerper=get_module_pref("koerper");
			if ($koerper == 1){
				$koerper=array(
					0=>array("ort"=>"Stirn", "motiv"=>""),
					1=>array("ort"=>"Linke Schläfe", "motiv"=>""),
					2=>array("ort"=>"Rechte Schläfe", "motiv"=>""),
					3=>array("ort"=>"Nase", "motiv"=>""),
					4=>array("ort"=>"Kinn", "motiv"=>""),
					5=>array("ort"=>"Linke Wange", "motiv"=>""),
					6=>array("ort"=>"Rechte Wange", "motiv"=>""),
					7=>array("ort"=>"Hals", "motiv"=>""),
					8=>array("ort"=>"Nacken", "motiv"=>""),
					9=>array("ort"=>"Linkes Schulterblatt", "motiv"=>""),
					10=>array("ort"=>"Rechtes Schulterblatt", "motiv"=>""),
					11=>array("ort"=>"Schultermitte", "motiv"=>""),
					12=>array("ort"=>"Rücken", "motiv"=>""),
					13=>array("ort"=>"Unterer Rücken", "motiv"=>""),
					14=>array("ort"=>"Linker Oberarm", "motiv"=>""),
					15=>array("ort"=>"Rechter Oberarm", "motiv"=>""),
					16=>array("ort"=>"Linker Unterarm", "motiv"=>""),
					17=>array("ort"=>"Rechter Unterarm", "motiv"=>""),
					18=>array("ort"=>"Linke Handfläche", "motiv"=>""),
					19=>array("ort"=>"Rechte Handfläche", "motiv"=>""),
					20=>array("ort"=>"Linker Handrücken", "motiv"=>""),
					21=>array("ort"=>"Rechter Handrücken", "motiv"=>""),
					22=>array("ort"=>"Linke Brust", "motiv"=>""),
					23=>array("ort"=>"Rechte Brust", "motiv"=>""),
					24=>array("ort"=>"Brustmitte", "motiv"=>""),
					25=>array("ort"=>"Bauch", "motiv"=>""),
					26=>array("ort"=>"Genitalbereich", "motiv"=>""),
					27=>array("ort"=>"Linker Oberschenkel", "motiv"=>""),
					28=>array("ort"=>"Rechter Oberschenkel", "motiv"=>""),
					29=>array("ort"=>"Linker Unterschenkel", "motiv"=>""),
					30=>array("ort"=>"Rechter Unterschenkel", "motiv"=>""),
					31=>array("ort"=>"Linker Knöchel", "motiv"=>""),
					32=>array("ort"=>"Rechter Knöchel", "motiv"=>""),
					33=>array("ort"=>"Linker Fuß", "motiv"=>""),
					34=>array("ort"=>"Rechter Fuß", "motiv"=>""),
				);
				set_module_pref("koerper", createstring($koerper));
			}else $koerper=createarray($koerper);
			
			output("<center><table border=1 cellpadding=5 cellspacing=0 bgcolor='#000000'>",true);
			output("<tr class='trhead'><td><b>Körperteil</b></td><td><b>Motiv</b></td><td><b>Aktion</b></td></tr>",true);
			for ($i=0; $i<=34; $i++){
				output("<tr class='".($i%2?"trlight":"trlight")."'><td>",true);
				output("`@%s", $koerper[$i]['ort']);
				output("</td><td>",true);
				if ($koerper[$i]['motiv'] == "") output("`2Noch keins.");
				else output("`@%s", $koerper[$i]['motiv']);
				output("</td><td>",true);
				if ($koerper[$i]['motiv'] == ""){
					output("<a href=\"runmodule.php?module=pdvtaet&op1=taetowieren&subop=".$i."&aktion=auswahl\">`@[Tätowieren]</a>",true);
					addnav("","runmodule.php?module=pdvtaet&op1=taetowieren&subop=".$i."&aktion=auswahl");	
				}else{
					output("<a href=\"runmodule.php?module=pdvtaet&op1=entfernen&subop=".$i."\">`\$[Entfernen]</a>",true);
					addnav("","runmodule.php?module=pdvtaet&op1=entfernen&subop=".$i."");		
				}
				output("</td></tr>",true);
			}
			output("</table></center>",true);
		}
		addnav("Zurück", "runmodule.php?module=wettkampf");
		
	page_footer();
}
?>