<?php

//alignment ready
//translator ready
//addnews ready

// Das kleine Wesen im Wald (f�r logd 0.98)
//
// Was ist das blo� f�r ein nerviges Ger�usch ...
//
// Erdacht und umgesetzt von Oliver Wellinghoff.
// E-Mail: wellinghoff@gmx.de
// Erstmals erschienen auf: http://www.green-dragon.info
//
// Z.T. Namen aus wettkampf.php 
//
//  - Version vom 01.03.2005 -

require_once("lib/systemmail.php");

function kleineswesen_getmoduleinfo(){
	$info = array(
		"name"=>"Das kleine Wesen im Wald",
		"version"=>"1.6",
		"author"=>"Oliver Wellinghoff",
		"category"=>"Forest Specials",
      	"download"=>"http://dragonprime.net/users/Harassim/kleineswesen098.zip",
		"settings"=>array(
			"Das kleine Wesen im Wald - Einstellungen,title",
			"verkleinert"=>"Wer ist gerade verkleinert|Violet",
		),
		"prefs"=>array(
			"Das kleine Wesen im Wald - Einstellungen,title",
			"zertreten"=>"Weniger Waldk�mpfe durch Zertreten|0",
			"gerettet"=>"Mehr Waldk�mpfe durch Rettung|0",			
		),
	);
	return $info;
}

function kleineswesen_install(){
	module_addeventhook("forest", "return 100;");
	module_addhook("validatesettings");
	module_addhook("newday");
	return true;
}

function kleineswesen_uninstall(){
	return true;
}

function kleineswesen_dohook($hookname,$args){
	global $session;

	switch($hookname){
		case "newday":
		//Kleines Wesen: Bonus und Malus
	
		$zertreten=get_module_pref("zertreten");
		$gerettet=get_module_pref("gerettet");
	
		if ($zertreten>=1){
			output("`n`)`\$Weil Du einen schlimmen Alptraum hattest, verlierst Du `^%s`\$ %s f�r heute!`n", $zertreten, translate($zertreten==1?"Runde":"Runden"));
			$session['user']['turns']-=$zertreten;
			set_module_pref("zertreten", "0");
		}elseif ($gerettet>=1){
			output("`n`)`@Weil Du einen fantastischen Traum hattest, erh�ltst Du `^%s`@ zus�tzliche Runden f�r heute!`n", $gerettet, translate($gerettet==1?"Runde":"Runden"));
			$session['user']['turns']+=$gerettet;
			set_module_pref("gerettet", "0");
		}
	}
	return $args;
	}

//Allgemeine Funktionen

function verkleinert_neu(){
	$auswahl=array("1" => "Violet",
	"2" => "Seth",
	"3" => "Chro'ghran",
	"4" => "Cedrik",
	"5" => "Tha",
	"6" => "Ghena",
	"7" => "Irog",
	"8" => "Merick",
	"9" => "Hannes VI.");
	$neu=e_rand(1,9);
	set_module_setting("verkleinert", $auswahl[$neu]);
}

//Start

function kleineswesen_runevent($type){
	global $session;
	$op = httpget('op');
	$from = "forest.php?";
	$result=get_module_setting("verkleinert");

	$session['user']['specialinc'] = "module:kleineswesen";

if ($session['user']['login'] == "$result"){

	if ($op=="" || $op=="search"){

		output("`@Ist das nicht ... doch, das ist der Ort, an dem Du neulich verkleinert wurdest! Du erschauderst "
			  ."und gehst mit schnellen Schritten weiter.`n`n Wobei, jetzt bist Du ja gar nicht mehr klein ... war "
			  ."das alles wom�glich nur eine Illusion? Ein Traum? Wer wei� ...`n`nAuf jeden Fall m�chtest Du hier "
			  ."nicht l�nger verweilen.");
		$session['user']['specialinc'] = "";
	}

}elseif ($op=="" || $op=="search"){

    output("`@Du ziehst durch den Wald und schwelgst in der selbstbewussten Gewissheit zuk�nftiger Heldentaten. In "
		  ."Gedanken schon fast beim Gr�nen Drachen angelangt, bleibst Du pl�tzlich genervt stehen. Dieses Piepen! "
		  ."Wie von einer Maus! Schon seit geraumer Zeit verfolgt es Dich ... Also jetzt reicht's aber!");
    output("`n`@Du b�ckst Dich, um den Boden abzusuchen. Das Piepen verstummt f�r einen Moment - woher kommt es? "
		  ."Dann wird es lauter und hektischer als je zuvor. Dort, zwischen den Bl�ttern: ein niedliches, kleines "
		  ."Wesen, kaum einen Fingernagel hoch, das Dir seltsam bekannt vorkommt. Dem Aussehen nach k�nnte es `#%s`@ "
		  ."sein ... Aber ist das denn m�glich?!`n`nWas wirst Du jetzt tun?", $result);
    output("`n`n`@<a href='forest.php?op=mitnehmen'>Wie s��! Ich nehme es mit.</a>", true);
    output("`@`n`n<a href='forest.php?op=zertreten'>Jetzt reicht's! Ich zertrete es.</a>", true);
    output("`@`n`n<a href='forest.php?op=ruhe'>Ich lasse es in Ruhe - so schlimm ist sein Piepen nun auch wieder nicht.</a>", true);
    addnav("", $from . "op=mitnehmen");
    addnav("", $from . "op=zertreten");
    addnav("", $from . "op=ruhe");
    addnav("Mitnehmen.", $from . "op=mitnehmen");
    addnav("Zertreten.", $from . "op=zertreten");
    addnav("In Ruhe lassen.", $from . "op=ruhe");

}elseif ($op=="mitnehmen"){

	output("`#'Ein Kinderspiel!'`@ denkst Du Dir. Aber weit gefehlt! Das kleine Wesen erweist sich als schnell und "
		  ."wendig. Du musst Dein ganzes Geschick aufbringen, um ihm bei seinen rasanten Haken zu folgen. Geb�ckt "
		  ."eilst Du von einem Busch zum n�chsten - und von einem Baum zum anderen. `n`nDein Ehrgeiz ist geweckt!");
	output("`@`n`n<a href='forest.php?op=mitnehmen2'>Weiter.</a>", true);
	addnav("", $from . "op=mitnehmen2");
	addnav("Weiter.", $from . "op=mitnehmen2");

}elseif ($op=="mitnehmen2"){

	switch(e_rand(1,10)){
		case 1: 
		case 2: 
		case 3:
		case 4:
			$result=get_module_setting("verkleinert");
			output("`@Minuten werden Stunden, Meter werden zu Kilometern ... In Deiner Euphorie ist Dir nicht "
				  ."aufgefallen, dass Du immer kleiner geworden bist - und das kleine Wesen immer gr��er! `n`#%s`@ "
				  ."steht nun �ber Dir und lacht. `n`n`#'Tja, was soll ich sagen? Wage es ja nicht, mir zu folgen "
				  ."und mich mit Deinen Hilfeschreien zu bel�stigen!'`n`n`@Das Lachen geht Dir nicht mehr aus dem "
				  ."Kopf ...`n`@Jetzt bist Du allein.`nIm Wald.`nKlein.`nAber niedlich!`nMm, dar�ber musst Du erst "
				  ."mal in Ruhe nachdenken ...", $result);
			output("`n`n`@Weil Du so niedlich geworden bist, erh�ltst Du `^1`@ Charmepunkt!");
			if ($session['user']['turns']<=4){
				output("`n`nDu bekommst `^%s`@ Erfahrungspunkte hinzu, verlierst aber `\$4`@ Waldk�mpfe!", round($session['user']['experience']*0.06));
				$session['user']['experience']=round($session['user']['experience']*1.06);
			}else{
				output("`n`nDu bekommst `^%s`@ Erfahrungspunkte hinzu, verlierst aber `\$4`@ Waldk�mpfe!", round($session['user']['experience']*0.04));
				$session[user]['experience']=round($session['user']['experience']*1.04);
			}
			$session['user']['turns']-=4;
			$session['user']['charm']++;
			addnav("Zur�ck in den Wald","forest.php");
			addnews("`4Von nun an muss sich `\$%s`4 im Wald vor K�fern in Acht nehmen!", $session['user']['name']);
			set_module_setting("verkleinert", $session['user']['login']);
			$session['user']['specialinc']="";
		break;
		case 5:
		case 6:
		case 7:
		case 8:
			$result=get_module_setting("verkleinert");
			output("`@Du jagst und jagst ... ein Gr�ner Drache ist nichts dagegen! Endlich bekommst Du das Wesen zu "
				  ."fassen. In dem Moment, in dem Du es ber�hrst, wirst Du zur�ckgeschleudert.`n`n Aus einer "
				  ."verpuffenden roten Wolke geht `#%s`@ hervor!", $result);
			$gems = e_rand(2,3); 
			output("`n`n`@Als Du die verlorengeglaubte Seele bis zum Dorfrand geleitet hast, bekommst Du f�r Deine "
				  ."ehrvolle Tat eine Belohnung in H�he von `^%s`@ Edelsteinen!", $gems);
			$session['user']['gems']+=$gems;
			if (is_module_active('alignment')) align("3");
			output("`n`nDu bekommst `^%s`@ Erfahrungspunkte hinzu und verlierst einen Waldkampf!", round($session['user']['experience']*0.02));
			$session['user']['experience']=round($session['user']['experience']*1.02);
			$session['user']['turns']-=1;
			addnav("Zur�ck in den Wald","forest.php");
			addnews("`@%s `2kehrte mit der verlorengeblaubten Seele `@%s`2 aus dem Wald zur�ck!", $session['user']['name'], $result);
			if ($result=="Violet" || $result=="Chro'ghran" || $result=="Seth" || $result=="Tha" || $result=="Ghena" || $result=="Irog" || $result=="Merick" || $result=="Hannes VI."){
				$session['user']['specialinc']="";
			}else{
		        $traum = (e_rand(3,5));
		        $sql="SELECT acctid FROM accounts WHERE login like '$result' LIMIT 1";
		        $result0=db_query($sql);
		        $rowgerettet = db_fetch_assoc($result0);
				set_module_pref("gerettet",$traum,"kleineswesen",$rowgerettet['acctid']);
				$mailmessage1 = array("`@Als Du heute erwachst, f�hlst Du Dich �u�erst erholt. In Deinem Traum warst "
					  ."Du ein kleines Wesen, kaum einen Fingernagel hoch und riefst verzweifelt um Hilfe. Niemand, "
					  ."dem Du im Wald begegnetest reagierte auf Dich ...`n Doch dann - endlich! - blieb jemand "
					  ."stehen. Es war `^%s`@! Stundenlang versuchte %s, Dich zu ber�hren, doch Du konntest nicht "
					  ."anders - irgendetwas zwang Dich, immer wieder wegzulaufen. Aber `^%s`@ lie� sich nicht "
					  ."beirren, ber�hrte Dich schlie�lich und errettete Dich damit. Als wenn das noch nicht genug "
					  ."gewesen w�re, geleitete %s Dich sogar noch bis zum Dorf zur�ck. `n`nWenn das kein Traum "
					  ."gewesen w�re, m�sstest Du %s nun �u�erst dankbar sein. Es war doch nur ein Traum, oder?`n`n"
					  ."Weil Du besonders gut geschlafen hast, wirst Du morgen `^%s`@ zus�tzliche Waldk�mpfe "
					  ."erhalten!`n`n", $session['user']['name'], translate($session['user']['sex']?"sie":"er"), $session['user']['name'], translate($session['user']['sex']?"sie":"er"), translate($session['user']['sex']?"ihr":"ihm"), $traum);
		        systemmail($rowgerettet['acctid'],"`@Du hattest einen fantastischen Traum!",$mailmessage1);
			}

			verkleinert_neu();
        	$session['user']['specialinc']="";
        break;            
        case 9:
	        output("`@Du jagst und jagst ... ein Gr�ner Drache ist nichts dagegen! Es wird immer sp�ter ... aber "
				  ."Dein Ehrgeiz ist - einmal entfacht - nicht aufzuhalten. Viele Stunden sind vergangen, als Deine "
				  ."k�rperlichen Kr�fte Dich verlassen. Du sinkst ersch�pft zu Boden - und siehst das kleine Wesen "
				  ."auf dem Baumstumpf vor Dir stehen. Es zu greifen w�re nun ein Kinderspiel, aber dazu reicht "
				  ."Deine Kraft nicht mehr aus ... Zum ersten Mal h�rst Du genau hin, was es Dir zu sagen hat:");
	        output("`#'Du hast Dich eifrig bem�ht, mich zu ber�hren - wer mich ber�hrt, macht mich frei! Leider "
				  ."darf ich es Dir nicht zu einfach machen ... Aber daf�r, dass Du es versucht hast, m�chte ich "
				  ."Dir etwas zeigen.'");
	        output("`n`@Das kleine Wesen h�pft dreimal im Kreis auf dem Baumstumpf herum, woraufhin dieser in "
				  ."einer roten Wolke verpufft. Es bleibt keine Spur von dem seltsamen Wesen - daf�r l�sst es aber "
				  ."ein kleines S�ckchen zur�ck!");
	        $gold =  e_rand(200,700) * e_rand(3,7);
	        output("`n`n`@In dem S�ckchen befinden sich `^%s`@ Goldst�cke!", $gold);
	        $turns = e_rand(1,2);
	        output("`n`n`^Du verlierst %s Waldk�mpfe!", $turns);
	        $session['user']['gold']+=$gold;
	        $session['user']['turns']-=$turns;
	        $session['user']['specialinc']="";
        break;
        case 10:
	        output("`@Du jagst und jagst ... ein Gr�ner Drache ist nichts dagegen! Es wird immer sp�ter ... aber "
				  ."Dein Ehrgeiz ist - einmal entfacht - nicht aufzuhalten. Viele Stunden sind vergangen, als Deine "
				  ."k�rperlichen Kr�fte Dich verlassen. Du sinkst ersch�pft zu Boden - und siehst das kleine Wesen "
				  ."auf dem Baumstumpf vor Dir stehen. Es zu greifen w�re nun ein Kinderspiel, aber dazu reicht "
				  ."Deine Kraft nicht mehr aus ... Zum ersten Mal h�rst Du genau hin, was es Dir zu sagen hat:");
	        output("`#'Du hast Dich eifrig bem�ht, mich zu ber�hren - wer mich ber�hrt, macht mich frei! Leider "
				  ."darf ich es Dir nicht zu einfach machen ... Na ja. Damit Du beim n�chsten Mal etwas wendiger "
				  ."bist, nimm diese Hilfe!'");
	        output("`n`@Das kleine Wesen h�pft dreimal im Kreis auf dem Baumstumpf herum, woraufhin dieser in einer "
				  ."roten Wolke verpufft. Es bleibt keine Spur von dem seltsamen Wesen - aber Du f�hlst Dich "
				  ."frischer als je zuvor!");
	        $turns = (e_rand(1,3));
	        $leben = (e_rand(1,2));
	        output("`n`n`@Du bekommst `^%s`@ permanente(n) Lebenspunkt(e)!", $leben);
	        output("`n`n`^Du verlierst %s Waldk�mpfe!", $turns);
	        $session['user']['turns']-=$turns;
	        $session['user']['maxhitpoints']+=$leben;
	        $session['user']['hitpoints']+=$leben;
	        $session['user']['specialinc']="";
        break;
	}

}elseif ($op=="zertreten"){

	output("`@Schweren Herzens hebst Du Deinen Fu�, schaust hinauf zu den Baumwipfeln und`n- trittst mit einem "
		  ."kr�ftigen Ruck zu!");
	output("`@`n`n<a href='forest.php?op=zertreten2'>Weiter.</a>", true);
	addnav("", $from . "op=zertreten2");
	addnav("Weiter.", $from . "op=zertreten2");

}elseif ($op=="zertreten2"){

	if (is_module_active('alignment')) align("-3");
	switch(e_rand(1,10)){
		case 1: 
			output("`@Als Du den Fu� wieder hebst, stellst Du mit Erstaunen fest, dass das kleine Wesen "
				  ."verschwunden ist. Offenbar hat er es mit der Angst bekommen und ist geflohen. Dir f�llt ein "
				  ."Stein vom Herzen - so ist es f�r alle Beteiligten besser. Erf�llt von neuer Frische setzt Du "
				  ."Deinen Weg fort.");
			output("`n`n`^Du erh�ltst einen zus�tzlichen Waldkampf!");
			$session['user']['turns']++;
			$session['user']['specialinc']="";
		break;
		case 2:
		case 3:
		case 4:
		case 5: 
		case 6:
		case 7:
			output("`@Als Du den Fu� wieder hebst, stellst Du angewidert fest, dass Du ganze Arbeit geleistet hast: von dem kleinen Wesen ist nur noch Matsch �brig geblieben. War das wirklich n�tig? Na ja, immerhin hat das Piepen aufgeh�rt. Aber Du brauchst eine Weile, um diesen Vorfall zu vergessen."); 
			output("`n`n`^Du verlierst einen Waldkampf!");
			$session['user']['turns']--;
			addnav("Zur�ck in den Wald","forest.php");
			$result=get_module_setting("verkleinert");
			addnews("`\$%s `4hat die Hilfeschreie von `\$%s`4 leider `iv�llig`i missverstanden ...", $session['user']['name'], $result);
			if ($result=="Violet" || $result=="Chro'ghran" || $result=="Seth" || $result=="Tha" || $result=="Ghena" || $result=="Irog" || $result=="Merick" || $result=="Hannes VI."){
				$session['user']['specialinc']="";
			}else{
		        $alptraum = (e_rand(3,5));
		        $sql="SELECT acctid FROM accounts WHERE login like '$result' LIMIT 1";
		        $result0=db_query($sql);
		        $rowzertreten = db_fetch_assoc($result0);
				set_module_pref("zertreten",$alptraum,"kleineswesen",$rowzertreten['acctid']);
		        $mailmessage1 = array("`@Heute nach wachst Du schwei�gebadet auf. In Deinem Traum warst Du ein "
					  ."kleines Wesen, kaum einen Fingernagel hoch und riefst verzweifelt um Hilfe. Niemand, dem Du "
					  ."im Wald begegnetest reagierte auf Dich ...`n Doch dann - endlich! - blieb jemand stehen. Es "
					  ."war `^%s`@! Aber %s blieb nicht stehen, um Dir zu helfen ...`n`n Es graut Dir noch immer "
					  ."bei der Erinnerung daran, wie es sich anf�hlte, als %s Fu� niederraste und Dich zermatschte. "
					  ."Aber zum Gl�ck war das alles ja nur ein Traum ... war es doch, oder?`n`nWeil Du schlecht "
					  ."geschlafen hast, wirst Du morgen `\$%s`@ Waldk�mpfe einb��en!`n`n", $session['user']['name'], translate($session['user']['sex']?"sie":"er"), translate($session['user']['sex']?"ihr":"sein"), $alptraum);
		        systemmail($rowzertreten['acctid'],"`\$Du hattest einen schrecklichen Alptraum!",$mailmessage1);
			}
			verkleinert_neu();
			$session['user']['specialinc']="";
        break;            
        case 8:
		case 9: 
        case 10:
			output("`@Erschrocken stellst Du fest, dass Dein Tritt kurz vor dem Boden gestoppt wurde. Von diesem "
				  ."kleinen Wesen?! - Zumindest ist es nicht so klein, als dass es Dich nicht gegen einen Baum "
				  ."schleudern k�nnte! Du rappelst Dich auf und rennst schreiend davon.");
			output("`n`n`^Du verlierst die meisten Deiner Lebenspunkte!");
			output("`n`^Du verlierst einen Waldkampf!");
			$session['user']['hitpoints']=1;
			$session['user']['turns']--;
			addnav("Zur�ck zum Wald.","forest.php");
			addnews("`\$%s `4wurde im Wald von einem D�umling erniedrigt.",$session['user']['name']);
			$session['user']['specialinc']="";
		break;
	}

}elseif ($op=="ruhe"){
	switch(e_rand(1,10)){ 
		case 1: 
		case 2: 
		case 3:
		case 4:
		case 5:
			output("`@Du rei�t Dich zusammen und musst das Piepen noch etliche Stunden ertragen. Aber letzten "
				  ."Endes war es wirklich nicht so schlimm.");
			$session['user']['specialinc']="";
		break;
		case 6: 
		case 7: 
		case 8: 
			output("`@Du rei�t Dich zusammen und musst das Piepen noch etliche Stunden ertragen. Letzten Endes war "
				  ."es aber wirklich nicht so schlimm.`n`n`^Du b��t nur einen einzigen Waldkampf ein!");
			$session['user']['turns']--;
			$session['user']['specialinc']="";
		break;            
		case 9: 
		case 10:
			output("`@Du rei�t Dich zusammen und musst das Piepen noch etliche Stunden ertragen. Arrrrrrgh! Wenn es "
				  ."doch blo� aufh�rte! Es bringt Dich beinahe um den Verstand.`n`n`^Weil Du Dich nicht "
				  ."konzentrieren kannst, b��t Du gleich zwei Waldk�mpfe ein!");
			$session['user']['turns']-=2;
			$session['user']['specialinc']="";
		break;
	}
}
}
function kleineswesen_run(){
}
?>
