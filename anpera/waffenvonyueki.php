<?php

function waffenvonyueki_getmoduleinfo(){
	$info = array(
		"name"=>"Yueki's kleines Waffenl�dchen",
		"version"=>"1.0",
		"author"=>"Daisuke",
		"category"=>"Village",
		"settings"=>array(
			"Yueki's kleines Waffenl�dchen Settings,title",
			"yuekiloc"=>"Wo soll der Laen erscheinen?,location|".getsetting("villagename", LOCATION_FIELDS),
                  "Slomogift,title",
                  "goldcost1"=>"Wieviel Gold soll das Slomogift kosten,int|5200",
                  "gemcost1"=>"Wieviel Gems soll das Slomogift kosten,int|5",
                  "Schleifstein,title",
                  "goldcost2"=>"Wieviel Gold soll der Schleifstein kosten,int|5200",
                  "gemcost2"=>"Wieviel Gems soll das Schleifstein kosten,int|5",
                  "Dolch,title",
                  "goldcost3"=>"Wieviel Gold soll der Dolch kosten,int|3000",
                  "gemcost3"=>"Wieviel Gems soll das Dolch kosten,int|3",
                  "Handschutz,title",
                  "goldcost4"=>"Wieviel Gold soll der Handschutz kosten,int|5200",
                  "gemcost4"=>"Wieviel Gems soll das Handschutz kosten,int|5",
                  "R�stungspolitur,title",
                  "goldcost5"=>"Wieviel Gold soll die R�stungspolitur kosten,int|5500",
                  "gemcost5"=>"Wieviel Gems soll das R�stungspolitur kosten,int|6",
            ),
        );
	return $info;
}

function waffenvonyueki_install() {
           module_addhook("village");
	return true;
}

function waffenvonyueki_uninstall(){
	return true;
}

function waffenvonyueki_dohook($hookname,$args){
	global $session;
	switch($hookname){

		case "village":
			if ($session['user']['location'] == get_module_setting("villagename","villagekioto")){
				tlschema($args['schemas']['marketnav']);
    			addnav($args['marketnav']);
    			tlschema();
				addnav("Yueki's kleines Waffenl�dchen","runmodule.php?module=waffenvonyueki");
			}
		break;
	}
	return $args;
}

function waffenvonyueki_run(){
       global $session;
       $op = httpget('op');
       page_header("Yueki's kleines Waffenl�dchen");

       $gold = $session['user']['gold'];
       $gems = $session['user']['gems'];
       $goldcost1 = get_module_setting("goldcost1");
       $gemcost1 = get_module_setting("gemcost1");
       $goldcost2 = get_module_setting("goldcost2");
       $gemcost2 = get_module_setting("gemcost2");
       $goldcost3 = get_module_setting("goldcost3");
       $gemcost3 = get_module_setting("gemcost3");
       $goldcost4 = get_module_setting("goldcost4");
       $gemcost4 = get_module_setting("gemcost4");
       $goldcost5 = get_module_setting("goldcost5");
       $gemcost5 = get_module_setting("gemcost5");

           if ($op == ""){
               output("`3 Du betritst einen Laden, der von au�en sehr wohlhabend aussieht, von innen aber noch pr�chtiger.");
               output("`$ 'Hallo wie kann ich Euch helfen?' ert�nt eine freundliche Stimme.");
               output("`3 Du tritst ein bischen n�her heran und erkennst eine h�nsche Elfin, ihr Name ist Yueki, du kennst sie schon aus den St�llen Tokyo's.");
               output("`$ 'Nimm dir Zeit und schau dich um.' `3 meint sie und k�mmert sich um ihre Auslagen.");
               addnav("Auslagen");
               addnav(array("`& Slomogift, f�r `^ %s Gold `& und `^ %s Edelsteine",$goldcost1,$gemcost1),"runmodule.php?module=waffenvonyueki&op=slomo");
               addnav(array("`& Schleifstein, f�r `^ %s Gold `& und `^ %s Edelsteine",$goldcost2,$gemcost2),"runmodule.php?module=waffenvonyueki&op=schleif");
               addnav(array("`& Dolch, f�r `^ %s Gold `& und `^ %s Edelsteine",$goldcost3,$gemcost3),"runmodule.php?module=waffenvonyueki&op=dolch");
               addnav(array("`& Handschutz, f�r `^ %s Gold `& und `^ %s Edelsteine",$goldcost4,$gemcost4),"runmodule.php?module=waffenvonyueki&op=hand");
               addnav(array("`& R�stungspolitur, f�r `^ %s Gold `& und `^ %s Edelsteine",$goldcost5,$gemcost5),"runmodule.php?module=waffenvonyueki&op=r�st");
               blocknav("runmodule.php?module=waffenvonyueki");
               }
           if ($op == "slomo"){
               if ($session['user']['gold'] >= $goldcost1 && $session['user']['gems'] >= $gemcost1) {
               output("Yueki �bergiebt dir das Slomogift und nimmt die $goldcost1 Goldst�cke und $gemcost1 Edelsteine von dir.");
               $session['user']['gold'] -= $goldcost1 && $session['user']['gems'] -= $gemcost1;
 		   apply_buff("slomo", array(
		      "name" => "`@Slomo-Gift",
			"rounds" => 15,
			"wearoff" => "`@Das Slomo-Gift h�rt auf zu wirken.",
			"badguydefmod" => 0.5,
			"roundmsg" => "`@Dein Gegner wird durch das Slomo-Gift verlangsamt und du hast keine Probleme, ihn zu treffen!",
			"survivenewday" => 1,
			"newdaymessage" => "`@Das Slomo-Gift auf deiner Waffe wirkt noch!")
               );
           }elseif ($session['user']['gold'] <= $goldcost1 && $session['user']['gems'] >= $gemcost1) {
           output("Yueki deutet auf den Preis von $goldcost1 Goldst�cken und du bemerkst, dass du nur $gold Goldst�cke bei dir hast.");
           }elseif ($session['user']['gold'] >= $goldcost1 && $session['user']['gems'] <= $gemcost1) {
           output("Yueki deutet auf den Preis von $gemcost1 Edelsteinen und du bemerkst, dass du nur $gems Edelsteine bei dir hast.");
           }elseif ($session['user']['gold'] <= $goldcost1 && $session['user']['gems'] <= $gemcost1) {
           output("Yueki deutet auf den Preis von $gemcost1 Edelsteinen und $goldcost1 Goldst�cken, als du bemerkst, dass du nur $gems Edelsteine und $gold Goldst�cke bei dir hast.");
           }
           }
           if ($op == "schleif"){
               if ($session['user']['gold'] >= $goldcost2 && $session['user']['gems'] >= $gemcost2) {
               output("Yueki �bergiebt dir den Schleifstein und nimmt die $goldcost2 Goldst�cke und $gemcost2 Edelsteine von dir.");
               $session['user']['gold'] -= $goldcost2 && $session['user']['gems'] -= $gemcost2;
 		   apply_buff("schleif", array(
		      "name" => "`3Extraschliff",
			"rounds" => 15,
			"wearoff" => "Der Schliff deiner Waffe ist durch die Angriffe auf {badguy} wieder abgestumpft.",
			"atkmod" => 1.2,
			"roundmsg" => "`3Durch den Schliff deiner Waffe verletzt du {badguy} mehr als vorher!",
			"survivenewday" => 1,
			"newdaymessage" => "`3Der Schliff deiner Waffe ist noch scharf!")
               );
           }elseif ($session['user']['gold'] <= $goldcost2 && $session['user']['gems'] >= $gemcost2) {
           output("Yueki deutet auf den Preis von $goldcost2 Goldst�cken und du bemerkst, dass du nur $gold Goldst�cke bei dir hast.");
           }elseif ($session['user']['gold'] >= $goldcost2 && $session['user']['gems'] <= $gemcost2) {
           output("Yueki deutet auf den Preis von $gemcost2 Edelsteinen und du bemerkst, dass du nur $gems Edelsteine bei dir hast.");
           }elseif ($session['user']['gold'] <= $goldcost2 && $session['user']['gems'] <= $gemcost2) {
           output("Yueki deutet auf den Preis von $gemcost2 Edelsteinen und $goldcost2 Goldst�cken, als du bemerkst, dass du nur $gems Edelsteine und $gold Goldst�cke bei dir hast.");
           }
           }
           if ($op == "dolch"){
               if ($session['user']['gold'] >= $goldcost3 && $session['user']['gems'] >= $gemcost3) {
               output("Yueki �bergiebt dir den Dolch und nimmt die $goldcost3 Goldst�cke und $gemcost3 Edelsteine von dir.");
               $session['user']['gold'] -= $goldcost3 && $session['user']['gems'] -= $gemcost3;
 		   apply_buff("dolch", array(
		      "name" => "`4Hinterhalt",
			"rounds" => 15,
			"wearoff" => "`4 {badguy} hat den Hinterhalt mit dem Dolch bemerkt und schl�gt ihn dir aus der Hand .",
			"atkmod" => 1.1,
                  "badguydefmod"=>0.9,
			"roundmsg" => "`4 Mit dem Dolch verletzt du {badguy} aus dem Hinterhalt!",
			"survivenewday" => 1,
			"newdaymessage" => "`4 Noch annst du den Dolch benutzen!")
               );
           }elseif ($session['user']['gold'] <= $goldcost3&& $session['user']['gems'] >= $gemcost3) {
           output("Yueki deutet auf den Preis von $goldcost3 Goldst�cken und du bemerkst, dass du nur $gold Goldst�cke bei dir hast.");
           }elseif ($session['user']['gold'] >= $goldcost3 && $session['user']['gems'] <= $gemcost3) {
           output("Yueki deutet auf den Preis von $gemcost3 Edelsteinen und du bemerkst, dass du nur $gems Edelsteine bei dir hast.");
           }elseif ($session['user']['gold'] <= $goldcost3 && $session['user']['gems'] <= $gemcost3) {
           output("Yueki deutet auf den Preis von $gemcost3 Edelsteinen und $goldcost3 Goldst�cken, als du bemerkst, dass du nur $gems Edelsteine und $gold Goldst�cke bei dir hast.");
           }
           }
           if ($op == "hand"){
               if ($session['user']['gold'] >= $goldcost4 && $session['user']['gems'] >= $gemcost4) {
               output("Yueki �bergiebt dir das Slomogift und nimmt die $goldcost4 Goldst�cke und $gemcost4 Edelsteine von dir.");
               $session['user']['gold'] -= $goldcost1 && $session['user']['gems'] -= $gemcost4;
 		   apply_buff("hand", array(
		      "name" => "`5Extraschutz",
			"rounds" => 15,
			"wearoff" => "`5 Die Angriffe von {badguy} habdne deinen Handschutz zerfetzt.",
			"defmod" => 1.3,
			"roundmsg" => "`5 Dein Handschutz sch�tzt dich etwas vor den Angriffen von {badguy}!",
			"survivenewday" => 1,
			"newdaymessage" => "`5 Dein Handschutz ist noch da als du aufwachst!")
               );
           }elseif ($session['user']['gold'] <= $goldcost4 && $session['user']['gems'] >= $gemcost4) {
           output("Yueki deutet auf den Preis von $goldcost4 Goldst�cken und du bemerkst, dass du nur $gold Goldst�cke bei dir hast.");
           }elseif ($session['user']['gold'] >= $goldcost4 && $session['user']['gems'] <= $gemcost4) {
           output("Yueki deutet auf den Preis von $gemcost4 Edelsteinen und du bemerkst, dass du nur $gems Edelsteine bei dir hast.");
           }elseif ($session['user']['gold'] <= $goldcost4 && $session['user']['gems'] <= $gemcost4) {
           output("Yueki deutet auf den Preis von $gemcost4 Edelsteinen und $goldcost4 Goldst�cken, als du bemerkst, dass du nur $gems Edelsteine und $gold Goldst�cke bei dir hast.");
           }
           }
           if ($op == "r�st"){
               if ($session['user']['gold'] >= $goldcost5 && $session['user']['gems'] >= $gemcost5) {
               output("Yueki �bergiebt dir das Slomogift und nimmt die $goldcost5 Goldst�cke und $gemcost5 Edelsteine von dir.");
               $session['user']['gold'] -= $goldcost5 && $session['user']['gems'] -= $gemcost5;
 		   apply_buff("r�st", array(
		      "name" => "`3blendender Glanz",
			"rounds" => 15,
			"wearoff" => "Durch den herumfliegenden Dreck im Kampf mit {badguy} ist der Glanz deiner Politur wieder weg.",
			"badguyatkmod" => 0.85,
			"roundmsg" => "`3Durch den Glanz deiner deiner R�stung sieht dich {badguy} nichtmehr so gut!",
			"survivenewday" => 1,
			"newdaymessage" => "`3Deine Waffe gl�nzt im Sonnenlicht!")
               );
           }elseif ($session['user']['gold'] <= $goldcost5 && $session['user']['gems'] >= $gemcost5) {
           output("Yueki deutet auf den Preis von $goldcost5 Goldst�cken und du bemerkst, dass du nur $gold Goldst�cke bei dir hast.");
           }elseif ($session['user']['gold'] >= $goldcost5 && $session['user']['gems'] <= $gemcost5) {
           output("Yueki deutet auf den Preis von $gemcost5 Edelsteinen und du bemerkst, dass du nur $gems Edelsteine bei dir hast.");
           }elseif ($session['user']['gold'] <= $goldcost5 && $session['user']['gems'] <= $gemcost5) {
           output("Yueki deutet auf den Preis von $gemcost5 Edelsteinen und $goldcost5 Goldst�cken, als du bemerkst, dass du nur $gems Edelsteine und $gold Goldst�cke bei dir hast.");
           }
           }
addnav("Anderes");
addnav("zur�ck zum Laden","runmodule.php?module=waffenvonyueki");
villagenav();
page_footer();
}
?>