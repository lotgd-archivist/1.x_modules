<?php
require_once("lib/addnews.php");
require_once("lib/systemmail.php");

function kneipenerweiterung_ale_run_private($args){
	global $session;
	$from = "runmodule.php?module=kneipenerweiterung";
	
	page_header("Trinken ...");
	switch($args){
		case "ausgeben":
			$kosten=10*$session['user']['level'];
			
			output("`2Wieviele Ales möchtest Du ausgeben? Cedrick schaut Dich prüfend an, als würde er von Dir die "
				."Höchstmenge erwarten. Du zählst Dein Geld durch und rechnest Dir aus, wozu es reicht ... ");
			if ($session['user']['gold'] < $kosten*5) output("Wie ärgerlich, es reicht nicht mal für fünf Ale "
				."und mit weniger bekommt man keine Runde zusammen!");
			
			$kosten1=$kosten*5;
			$kosten2=$kosten*10;
			$kosten3=$kosten*15;
			$kosten4=$kosten*20;
			$kosten5=$kosten*25;
			$kosten6=$kosten*30;
				
			addnav("Wieviele?");
			if ($session['user']['gold'] >= $kosten1) addnav("5 Ales (für `^$kosten1`0)", $from."&op=ausgegeben&subop=5");
			if ($session['user']['gold'] >= $kosten2) addnav("10 Ales (für `^$kosten2`0)", $from."&op=ausgegeben&subop=10");
			if ($session['user']['gold'] >= $kosten3) addnav("15 Ales (für `^$kosten3`0)", $from."&op=ausgegeben&subop=15");
			if ($session['user']['gold'] >= $kosten4) addnav("20 Ales (für `^$kosten4`0)", $from."&op=ausgegeben&subop=20");
			if ($session['user']['gold'] >= $kosten5) addnav("25 Ales (für `^$kosten5`0)", $from."&op=ausgegeben&subop=25");
			if ($session['user']['gold'] >= $kosten6) addnav("30 Ales (für `^$kosten6`0)", $from."&op=ausgegeben&subop=30");
			addnav("Doch nicht ...");
			addnav("Zurück", "inn.php?op=bartender");
		break;
		case "ausgegeben":
			$subop= $_GET[subop];	
			$menge=$subop;
			$kosten=10*$session['user']['level']*$menge;
			$session['user']['gold']-=$kosten;
			output("`2Du schmeißt eine Runde von `^%s`2 Ales und bezahlst dafür `^%s`2 Goldstücke. Ein Raunen "
				."geht durch die Kneipe, als Cedrick Deine Tat verkündet!", $menge, $kosten);
			set_module_setting("bezahlt", $menge, "kneipenerweiterung");
			
			if ($menge == 30){
				$text=translate_inline("`2Cedrick füllt `^".$menge."`2 Krüge mit Freiale auf `@".$session['user']['name']."`2's Kosten! ".($session['user']['sex']?"Der edlen Spenderin":"Dem edlen Spender")." sei ewiger Dank!");
				addnews("`@%s `2hat eine Runde Ale in der Kneipe spendiert!", $session[user][name]);
			}
			else $text=translate_inline("`2Cedrick füllt `^".$menge."`2 Krüge mit Freiale auf `@".$session['user']['name']."`2's Kosten!");
			system_commentary(inn, $text, $schema=false);
									
			addnav("Zurück", "inn.php?op=bartender");
		break;
		case "trinken":
			$menge=get_module_setting("bezahlt", "kneipenerweiterung");
			if ($menge == 0){
				output("`2Es geht doch nichts über ein leckers, frisches Ale, das nichts - Mist, jemand "
					." hat Dir das letzte Freiale vor der Nase weggeschnappt!");
			}else{
				$neu=$menge-1;
				set_module_setting("bezahlt", $neu, "kneipenerweiterung");
				
				$drunk=get_module_pref("drunkeness", "drinks");
				$neu=$drunk+(e_rand(30,40));
				set_module_pref("drunkeness", $neu, "drinks");
				
				output("`2Es geht doch nichts über ein leckers, frisches Ale, das nichts kostet ... ");
				if ($drunk >= 66) output("hehe, und Cedrick hat es nicht mal gemerkt, obwohl er Dich "
					."wegen Deiner Trunkenheit schon nicht mehr bedienen wollte!");
				set_module_pref("getrunken", 1, "kneipenerweiterung");
			}
			addnav("Zurück", "inn.php?op=bartender");
		break;
		case "auswirkung":
			global $session;
			if ($session[user][race]=="Elf") {
				output("`4Du hast zuviel gesoffen und bist an einer Alkoholvergiftung gestorben.`n`n ");
				output("Du verlierst 5% deiner Erfahrungspunkte und die Hälfte deines Goldes!`n`n");
				output("Du kannst morgen wieder spielen.");
				$session[user][alive]=false;
				$session[user][hitpoints]=0;
				$session[user][gold]=$session[user][gold]*0.5;
				$session[user][experience]=$session[user][experience]*0.95;
				addnews("`\$%s`4 hat %s zarten Elfenkörper in der Kneipe mit Alkohol geschunden und ist tot vom Barhocker gekippt.", $session[user][name], ($session[user][sex]?"ihren":"seinen"));
				addnav("Zu den Neuigkeiten", "news.php");
				$text=translate_inline("`4Zugrundegerichtet vom vielen Alkohol kippt ".($session['user']['sex']?"die zarte Elfe":"der zarte Elf")." `\$".$session['user']['name']."`4 vom Barhocker ... tot.");
				system_commentary(inn, $text, $schema=false);
			}else if ($session[user][race]=="Dwarf"){
				switch(e_rand(1,10)){
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
						output("`4Du hast zwar zuviel gesoffen, aber da ein Zwerg einiges vertragen kann, hast Du es gerade noch überlebt, nachdem Du in der Schenke eine Sauerei angerichtet hattest.`n");
						output("Du verlierst den Großteil Deiner Lebenspunkte!");
						$session[user][hitpoints]=1;
						set_module_pref("drunkeness", 98, "drinks");
						addnews("%s `7entging nur knapp den Folgen einer Alkoholvergiftung, weil %s ist ...", $session[user][name], ($session[user][sex]?"sie eine Zwergin":"er ein Zwerg"));
						addnav("Torkel hinaus ...","village.php");
						$text=translate_inline("`7".($session['user']['sex']?"Die besoffene Zwergin":"Der besoffene Zwerg")." `&".$session['user']['name']."`7 fällt verkrampft zu Boden, übergibt sich und torkelt dann völlig apathisch nach draußen ...");
						system_commentary(inn, $text, $schema=false);
					break;
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
						output("`4Du hast selbst für ".($session[user][sex]?"eine Zwergin":"einen Zwerg")." zuviel gesoffen und bist an einer Alkoholvergiftung gestorben.`n`n ");
						output("Du verlierst 5% deiner Erfahrungspunkte und die Hälfte deines Goldes!`n`n");
						output("Du kannst morgen wieder spielen.");
						$session[user][alive]=false;
						$session[user][hitpoints]=0;
						$session[user][gold]=$session[user][gold]*0.5;
						$session[user][experience]=$session[user][experience]*0.95;
						addnews("`4Der legendären zwergischen Trinkfestigkeit zum Trotz starb `\$%s`4 in der Kneipe an einer Überdosis Alkohol ...", $session[user][name]);
						addnav("Zu den Neuigkeiten", "news.php");
						$text=translate_inline("`4".($session['user']['sex']?"Die besoffene Zwergin":"Der besoffene Zwerg")." `\$".$session['user']['name']."`4 fällt verkrampft zu Boden und erstickt an ".($session['user']['sex']?"ihrem":"seinem")." eigenen Erbrochenen ...");
						system_commentary(inn, $text, $schema=false);
					break;
				}
			}else{
				switch(e_rand(1,10)){
					case 1:
					case 2:
					case 3:
						output("`4Du hast zwar zuviel gesoffen, es aber gerade noch überlebt. Nachdem Du Dich auf die "
							."Theke übergeben hast, wirft Cedrick Dich aber raus.`n");
						output("Du verlierst den Großteil Deiner Lebenspunkte!");
						set_module_pref("drunkeness", 98, "drinks");
						$session[user][hitpoints]=1;
						addnews("%s `7entging nur knapp den Folgen einer Alkoholvergiftung, indem %s sich unter Krämpfen erbrach! Auf Cedricks Theke ...", $session[user][name], ($session['user']['sex']?"sie":"er"));
						addnav("Rauswurf!","village.php");
						$text=translate_inline("`7".($session['user']['sex']?"Die völlig betrunkene":"Der völlig betrunkene")." `&".$session['user']['name']."`7 übergibt sich auf die Theke und wird von Cedrick hinausgeworfen ...");
						system_commentary(inn, $text, $schema=false);
					break;
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
						output("`4Du hast zuviel gesoffen, bist im Suff gestolpert und mit dem Hinterkopf aufgeschlagen.`n`n ");
						output("Du verlierst 5% deiner Erfahrungspunkte und die Hälfte deines Goldes!`n`n");
						output("Du kannst morgen wieder spielen.");
						$session[user][alive]=false;
						$session[user][hitpoints]=0;
						$session[user][gold]=$session[user][gold]*0.5;
						$session[user][experience]=$session[user][experience]*0.95;
						addnews("`\$%s `4starb in der Kneipe an einer Überdosis Alkohol ...", $session[user][name]);
						addnav("Zu den Neuigkeiten", "news.php");
						$text=translate_inline("`4".($session['user']['sex']?"Die völlig betrunkene":"Der völlig betrunkene")." `\$".$session['user']['name']."`4 stolpert im Suff, schlägt mit dem Hinterkopf auf und liegt reglos auf dem Boden ... tot.");
						system_commentary(inn, $text, $schema=false);
					break;
				}
			}
		break;
	}
	page_footer();
}
?>