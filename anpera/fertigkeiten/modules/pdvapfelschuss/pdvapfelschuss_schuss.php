<?php

require_once("lib/fert.php");
require_once("lib/systemmail.php");
require_once("lib/commentary.php");
require_once("lib/addnews.php");

function pdvapfelschuss_schuss_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Der schmierige Schie�stand");
	$schuetze=httpget("subop");
	$ziel=get_module_setting("ziel");
	$fw=get_module_setting("fw");
		
	$sql = "SELECT name, gold FROM ".db_prefix("accounts")." WHERE acctid='$schuetze'";
	$results = db_query($sql);
	$row = db_fetch_assoc($results);
		
	$name=$row['name'];
	$gold=$row['gold'];
			
	//Zielschwierigkeit + Zufallsmod je nachdem, wie gut der Bogen ist
		if ($ziel == 1) $typ=-35;
		else $typ=-25;
		$mod=$typ - e_rand(0,15);
		
	//Die Probe
		$probe=probe($fw, $mod);
		$wert=$probe[wert];
	
	//Trefferbestimmung, wenn daneben, aber kein Kopftreffer
	//Per Zufall - eine genauere Bestimmung �ber die Probe
	//w�re nett und machbar, aber das hier ist f�r das Rollenspiel
	//genauso zweckm��ig	
	switch(e_rand(1,15)){
		case 1:		$hit="am Bauch"; break;
		case 2:		$hit="an der Schulter"; break;
		case 3:		$hit="am Fu�"; break;
		case 4:		$hit="an der Hand"; break;
		case 5:		$hit="am Brustkorb"; break;
		case 6:		$hit="an den Genitalien"; break;
		case 7:		$hit="am Knie"; break;
		case 8:		$hit="am Ellenbogen"; break;
		case 9:		$hit="am Hals"; break;
		case 10:	$hit="an der H�fte"; break;
		case 11:	$hit="am Ohr"; break;
		case 12:	$hit="an der Wange"; break;
		case 13:	$hit="am Unterarm"; break;
		case 14:	$hit="am Schienbein"; break;
		case 15:	$hit="an der Schl�fe"; break;
	}
		
		
	switch($ziel){
		case 1:
			if ($wert >= 0) $ergebnis=1;
			else if ($wert < 0 && $wert >= -20) $ergebnis=2;
			else if ($wert < -20 && $wert >= -50) $ergebnis=3; 
			else if ($wert < -50) $ergebnis=4; 
		
			output("`@%s`@ spannt den Bogen ... nimmt Ziel ... und ... ", $name);
			if (is_module_active('alignment')) align("-1");
			switch ($ergebnis){
				case 1:
					$goldbonus=get_module_setting("preis", "pdvapfelschuss") * 2;
					$goldneu=$gold+$goldbonus;
					
					$sql = "UPDATE accounts SET gold=$goldneu WHERE acctid=$schuetze";
                    db_query($sql) or die(sql_error($sql));
					
					output("trifft! Der Apfel ist nun fachm�nnisch an der Wand befestigt "
						."und Du kannst aufatmen. Der schmierige Troll ist sehr verwundert und zahlt "
						."%s`@ widerwillig `^%s`@ Goldst�cke Preisgeld aus. Herzlichen Gl�ckwunsch "
						."zum �berleben!", $name, $goldbonus);
					systemmail($schuetze,"`@Treffer!","`@Du hast die Herausforderung "
						."am Schie�stand, ".$session['user']['name']."`@ einen Apfel vom Kopf "
						."zu schie�en mit Bravour gemeistert. `^".$goldbonus."`@ Goldst�cke Preisgeld "
						."sind nun Dein!");
										
					addnews_for_user($schuetze ,"`@%s`2 ist es gelungen, `@%s`2 einen Apfel vom Kopf zu schie�en! Eine wahre Meisterleistung!", $name, $session['user']['name']);
				break;
				case 2:
					output("trifft - Dich in den Kopf! Selbst ein herbeieilender Heiler kann nicht "
						."mehr helfen und nur mit M�he und Not gelingt es, Deinen fachm�nnisch an die Wand "
						."montierten Kopf wieder loszubekommen ...`n`n"
						."`\$Du bist tot!`n`nDu verlierst `^%s`\$ Erfahrungspunkte und kannst morgen weiterspielen!"
						."", round($session['user']['experience']*0.05));
					
					$session['user']['alive']=false;
			        $session['user']['hitpoints']=0;
			        $session['user']['gold']=0;							
					$session['user']['experience']*=0.95;
						
					systemmail($schuetze,"`\$Treffer!","`\$Bei dem Versuch, ".$session['user']['name']."`\$ "
						."einen Apfel vom Kopf zu schie�en hast Du ".($session['user']['sex']?"sie":"ihn")." "
						."mit einem Kopfschuss get�tet! Bei allen G�ttern, wie willst Du das wiedergutmachen?!");
					addnews_for_user($schuetze ,"`\$%s`4 hat `\$%s`4 beim Schuss auf den Apfel mit einem Kopfschuss get�tet!", $name, $session['user']['name']);
				break;
				case 3:
					output("trifft - Dich! Dadurch wirst Du %s verletzt! Ein herbeieilender Heiler kann das Schlimmste "
						."verhindern, weshalb Du nur leichte Verletzungen davontr�gst ... das ging gerade noch mal gut.", $hit);
					systemmail($schuetze,"`4Treffer ...","`4Bei dem Versuch, ".$session['user']['name']."`4 "
						."einen Apfel vom Kopf zu schie�en hast Du ".($session['user']['sex']?"sie":"ihn")." "
						."".$hit." verletzt! Ein herbeieilender Heiler hat aber das Schlimmste verhindern k�nnen ... "
						."Vielleicht w�re eine Entschuldigung angebracht?");
					
					$session['user']['hitpoints']*=0.75;											
					
					addnews_for_user($schuetze ,"`\$%s`4 hat `\$%s`4 beim Schuss auf den Apfel %s verletzt!", $name, $session['user']['name'], $hit);	
				break;
				case 4:
					output("schie�t daneben! Man klopft %s`@ f�r den Versuch anerkennend auf die Schulter und Du "
						."bist froh, ohne jeden Schaden davongekommen zu sein ...", $name);
					systemmail($schuetze,"`\$Daneben!","`\$Bei dem Versuch, ".$session['user']['name']."`\$ einen "
						."Apfel vom Kopf zu schie�en hast Du danebengeschossen. Na ja, immerhin gab es keine Verletzten ...");
					addnews_for_user($schuetze ,"`\$%s`4 hat bei dem Schuss auf den Apfel auf `\$%s`4's Kopf danebengeschossen!" , $name ,$session['user']['name']);
				break;
			}
		break;	
		case 2:
			if ($wert >= 0) $ergebnis=1;
			else if ($wert < 0 && $wert >= -50) $ergebnis=2;
			else if ($wert < -50) $ergebnis=3; 
			output("`@%s`@ spannt den Bogen ... nimmt Ziel ... und ... ", $name);
			if (is_module_active('alignment')) align("-2");
			switch ($ergebnis){
				case 1:
					output("trifft - Deinen Kopf! Er ist nun fachm�nnisch an der Wand befestigt "
						."und %s`@ weidet sich an dem Entsetzen der Zuschauer. Es ist recht "
						."offensichtlich, dass dieser Schuss Absicht war ...`n`n"
						."`\$Du bist tot!`n`nDu verlierst `^%s`\$ Erfahrungspunkte und kannst morgen weiterspielen!"
						."", $name, round($session['user']['experience']*0.05));
					
					$session['user']['alive']=false;
			        $session['user']['hitpoints']=0;
			        $session['user']['gold']=0;							
					$session['user']['experience']*=0.95;
						
					systemmail($schuetze,"`@Treffer!","`@Bei dem Versuch, ".$session['user']['name']."`@ "
						."einen Apfel vom Kopf zu schie�en hast Du ".($session['user']['sex']?"sie":"ihn")." "
						."absichtlich mit einem Kopfschuss get�tet! Guter Schuss ...");
					addnews_for_user($schuetze ,"`\$%s`4 hat `\$%s`4 beim Schuss auf den Apfel mit einem Kopfschuss get�tet! So mancher Zuschauer sieht es als erwiesen an, dass dies volle Absicht war ..." , $name ,$session['user']['name']);
				break;
				case 2:
					output("trifft - Dich! Dadurch wirst Du %s verletzt! Ein herbeieilender Heiler kann das Schlimmste "
						."verhindern ... das ging gerade noch mal gut. %s`@'s abf�lligem Grinsen ist "
						."deutlich zu entnehmen, dass Du eigentlich sogar h�ttest sterben sollen ...", $hit, $name);
					systemmail($schuetze,"`4Treffer ...","`4Bei dem Versuch, ".$session['user']['name']."`4 "
						."mit einem Kopfschuss zu t�ten hast Du ".($session['user']['sex']?"sie":"ihn")." "
						."".$hit." verletzt! Ein herbeieilender Heiler hat aber das Schlimmste verhindern k�nnen ... "
						."Na ja, immerhin nicht ganz daneben ...");
					
					$session['user']['hitpoints']*=0.75;		
					
					addnews_for_user($schuetze ,"`\$%s`4 hat `\$%s`4 beim Schuss auf den Apfel %s verletzt! So mancher Zuschauer sieht es �brigens als erwiesen an, dass der Kopf das eigentliche Ziel gewesen sein d�rfte ..." ,$name, $session['user']['name'], $hit);
				break;
				case 3:
					output("schie�t daneben! Man klopft %s`@ f�r den Versuch anerkennend auf die Schulter, "
						."aber Du bist Dir angesichts dieses �beraus entt�uschten Gesichtes sicher, "
						."dass ohnehin nicht der Apfel h�tte getroffen werden sollen ...", $name);
					systemmail($schuetze,"`\$Daneben!","`\$Bei dem Versuch, ".$session['user']['name']."`\$ mit "
						."einem Kopfschuss zu t�ten, hast Du danebengeschossen. Du bist schwach ...");
					addnews_for_user($schuetze ,"`\$%s`4 hat bei dem Schuss auf den Apfel auf `\$%s`4's Kopf danebengeschossen! So mancher Zuschauer sieht es �brigens als erwiesen an, dass der Kopf das eigentliche Ziel gewesen sein d�rfte ..." , $name ,$session['user']['name']);
				break;
			}
		break;
	}
	set_module_pref("teilnahme", 1);
	set_module_setting("schuetze", 0);
	if ($session['user']['alive'] == true) addnav("Zur�ck", "runmodule.php?module=wettkampf");
	else addnav("Zu den Neuigkeiten", "news.php");
	page_footer();
}
?>