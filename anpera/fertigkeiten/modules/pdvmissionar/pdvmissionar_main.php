<?php

function pdvmissionar_main_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Der Missionar");
	
	checkday();
		output("`@Du n�herst Dich einem kleinen, rechteckigen Zelt, unter dessen Vordach ein freundlich aussehender, wei�haariger Mann steht und die Leute beobachtet. ");
	$mindk = get_module_setting("mindk", "racevanthira");
	if ($session['user']['ctitle'] == "`\$Ramius� ".($session[user][sex]?"Sklavin":"Sklave").""){
		output("`@Als er Dich sieht, kommt er einen Schritt auf Dich zu: `#'Verlorene Seele, Du ... f�r einen kleinen Obulus von nur 10 Edelsteinen befreie ich Dich von der Sklaverei.'");		
		if ($session[user][gems] >= 7){
			output("`@`n`n<a href='runmodule.php?module=pdvmissionar&op1=sklaverei'>Bitte befreit mich von meinem Leid!</a>", true);
			addnav("Befreit mich!","runmodule.php?module=pdvmissionar&op1=sklaverei");
			addnav("","runmodule.php?module=pdvmissionar&op1=sklaverei");	
		}
		output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>Blasphemie! Ich bleibe bei meinem Herrn!</a>", true);
		addnav("Zur�ck","runmodule.php?module=wettkampf&op1=");
		addnav("","runmodule.php?module=wettkampf&op1=");
	}else if ($session[user][race] != "Vanthira" && $session['user']['dragonkills'] >= $mindk){
		$race=$session[user][race];
		if ($race == "Dwarf") $race="Zwerg";
		else if ($race == "Human") $race="Mensch";
			
		output("`@Als er Dich sieht, kommt er einen Schritt auf Dich zu: `#'Sucht auch Ihr den `iAusgleich`i? - `iWir`i haben ihn gefunden. `iWir`i k�nnen ihn Euch geben. Gebt uns einfach Euer Leben.'");
		output("`@`n`n<a href='runmodule.php?module=pdvmissionar&op1=bekehrt'>Mein Leben als %s ist ohnehin nichts wert - nehmt es!</a>", $race,true);
		output("`@`n`n<a href='runmodule.php?module=wettkampf&op1='>�hm, ja. Ich komme dann sp�ter wieder ...</a>", true);
		addnav("","runmodule.php?module=pdvmissionar&op1=bekehrt");
		addnav("","runmodule.php?module=wettkampf&op1=");
		addnav("Ich gebe mein Leben.","runmodule.php?module=pdvmissionar&op1=bekehrt");
		addnav("Zur�ck - `imit`i meinem Leben.","runmodule.php?module=wettkampf&op1=");
	}else if ($session[user][race] != "Vanthira" && $session['user']['dragonkills'] < $mindk){
		output("`@Als er Dich sieht, l�chelt er skeptisch und sagt: `#'Du bist noch nicht bereit f�r die Weihen ... kehre wieder, wenn Dich Dein Schicksal hat reifer werden lassen ...'");
		addnav("Zur�ck","runmodule.php?module=wettkampf&op1=");
		addnav("","runmodule.php?module=wettkampf&op1=");
	}else{
		output("`@Als er Dich sieht, l�chelt er zufrieden und sagt: `#'Sei gegr��t, %s, und denke immer daran: `iWir`i haben den Ausgleich bereits gefunden. Berichte �berall von unserem Gl�ck!'", ($session[user][sex]?"meine Schwester":"mein Bruder"));
		addnav("Zur�ck","runmodule.php?module=wettkampf&op1=");
		addnav("","runmodule.php?module=wettkampf&op1=");
	}
	page_footer();
}
?>