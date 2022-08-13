<?php

//allignment ready

// Der Fremde, Version (für logd 0.98)
//
// Ist es ein Gott? Ein Dämon?
// Oder doch nur Einbildung ...
//
// Erdacht und umgesetzt von Oliver Wellinghoff.
// E-Mail: wellinghoff@gmx.de
// Erstmals erschienen auf: http://www.green-dragon.info
//
//  - 29.06.04 -
//  - Version vom 01.03.2005 -
//
//  Besonderheiten für die Rassen "Vanthira" und "Vampir"!

require_once ("lib/names.php");
require_once("lib/commentary.php");

function derfremde_getmoduleinfo(){
    $info = array(
        "name"=>"Der Fremde",
        "version"=>"1.32",
        "author"=>"Oliver Wellinghoff",
        "category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/users/Harassim/derfremde098.zip",
		"settings"=>array(
            "Anpreisungen,title",
			"Gott_1"=>"1. Gottheit von Rasse:|Troll",
			"Anpreisung_1"=>"-> Anpreisung:|Crogh-Uuuhl, Beleber der Sümpfe, Herr der Trolle - Gott der Götter!",
			"Gott_2"=>"2. Gottheit von Rasse:|Elf",
    		"Anpreisung_2"=>"-> Anpreisung:|Chara, Herrin der Wälder, Licht durch die Baumkronen - Göttin der Götter!",
			"Gott_3"=>"3. Gottheit von Rasse:|Human",
			"Anpreisung_3"=>"-> Anpreisung:|einäugiger Odin, Herr der Asen und der Menschen - Gott der Götter!",
			"Gott_4"=>"4. Gottheit von Rasse:|Dwarf",
			"Anpreisung_4"=>"-> Anpreisung:|Zrarek, Allmächtiger, mein Gottkaiser, Herr der Zwerge, Beschützer Drassorias - Gott der Götter!",
			"Gott_5"=>"5. Gottheit von Rasse:|Echse",
			"Anpreisung_5"=>"-> Anpreisung:|Sssslassarrr, Hüterin der Hochebenen von Chrizzak, Herrin der Echsen - Göttin der Götter!",
			"Gott_6"=>"6. Gottheit von Rasse:|Felyne",
			"Anpreisung_6"=>"-> Anpreisung:|Thei'gra, lautloser Atem des Dschungels, Herrin der Raubkatzen - Göttin der Götter!",
			"Gott_7"=>"7. Gottheit von Rasse:|Vampir",
			"Anpreisung_7"=>"-> Anpreisung:|`\$Ramius`#, Gebieter über die Macht der Dunklen Mutter, Herrscher der Unterwelt, mein Daseinsspender - Gott der Götter!",
			"Gott_8"=>"8. Gottheit von Rasse:|Frei.",
			"Anpreisung_8"=>"-> Anpreisung:|Frei.",
			"Gott_9"=>"9. Gottheit von Rasse:|Frei.",
			"Anpreisung_9"=>"-> Anpreisung:|Frei.",
			"Gott_10"=>"10. Gottheit von Rasse:|Frei.",
			"Anpreisung_10"=>"-> Anpreisung:|Frei.",
			"Ramius' Launen,title",
			"Besondere Gnade / Besonderer Zorn,note",
			"ramius1_turn"=>"WK-Bonus ,floatrange,1,5|2",			
			"ramius1_hp"=>"HP-Bonus ,floatrange,1.15,1.25,0.01|1.15",			
			"ramius1_rounds"=>"Buff Dauer ,floatrange,150,200|200",			
			"ramius1_attkmod"=>"Buff AT-Mod ,floatrange,1.15,1.35|1.15",			
			"Gnade,note",
			"ramius2_turn"=>"WK-Bonus ,floatrange,1,5|2",			
			"ramius2_hp"=>"HP-Bonus ,floatrange,1.15,1.25,0.01|1.10",			
			"ramius2_rounds"=>"Buff Dauer ,floatrange,100,200,|150",			
			"ramius2_attkmod"=>"Buff AT-Mod ,floatrange,1.1,1.50,0.01|1.1",			
			"Zorn,note",
			"ramius3_turn"=>"WK-Malus ,floatrange,1,5|2",			
			"ramius3_hp"=>"HP-Malus ,floatrange,0.5,0.9,0.01|0.9",			
			"ramius3_rounds"=>"Buff Malus ,floatrange,150,250|200",			
			"ramius3_defmod"=>"Buff DEF-Mod (Malus) ,floatrange,0.5,0.9,0.01|0.9",			
		),
	);
    return $info;
}

function derfremde_install(){
    module_addeventhook("forest", "return 100;");
    module_addhook("newday");
    return true;
}

function derfremde_uninstall(){
    return true;
}

function derfremde_dohook($hookname,$args){
	global $session;
    switch($hookname){
	case "newday":
	//Der Fremde: Bonus und Malus
			if ($session['user']['ctitle']=="`\$Ramius´ ".($session['user']['sex']?"Sklavin":"Sklave").""){
				if  (is_module_active('alignment')){
				$alignment = get_align();
				$evil=get_module_setting('evilalign','alignment');
				$good=get_module_setting('goodalign','alignment');
				if ($alignment < $evil){
	        		output("`\$`nDein Herr, Ramius, ist begeistert von Deinen Greueltaten und gewährt Dir seine `bbesondere`b Gnade!`n");
		    		output("`\$Seine Gnade ist heute besonders ausgeprägt - und Du erhältst 2 zusätzliche Waldkämpfe!`n");
            		$turns=get_module_setting("ramius1_turn");
            		$hpbonus=get_module_setting("ramius1_hp");
            		$rounds=get_module_setting("ramius1_rounds");
            		$attkmod=get_module_setting("ramius1_attkmod");
		    		$session['user']['turns']+=$turns;
	   				$session['user']['hitpoints']*=$hpbonus;
					$session['bufflist']['Ramius1'] = array("name"=>"`\$Ramius´ `bbesondere`b Gnade","rounds"=>$rounds,"wearoff"=>"`\$Ramius hat Dir für heute genug geholfen.","atkmod"=>$attkmod,"roundmsg"=>"`\$Eine Stimme in Deinem Kopf befiehlt: `i`bZerstöre!`b Bring Leid über die Lebenden!`i");
				}else if ($alignment > $good){
					output("`\$`nDein Herr, Ramius, ist geradezu erbost über Deine Gutmütigkeit! Sein `bbesonderer`b Zorn lastet auf Dir!`n");
		    		output("`\$Seine Zorn ist heute besonders ausgeprägt - und Du verlierst 2 Waldkämpfe!`n");
            		$turns=get_module_setting("ramius1_turn");
            		$hpbonus=2-get_module_setting("ramius1_hp");
            		$rounds=get_module_setting("ramius1_rounds");
            		$attkmod=2-get_module_setting("ramius1_attkmod");
		    		$session['user']['turns']-=$turns;
	   				$session['user']['hitpoints']*=2-$hpbonus;
					$session['bufflist']['Ramius4'] = array("name"=>"`\$Ramius´ `bbesonderer`b Zorn","rounds"=>$rounds,"wearoff"=>"`\$Ramius´ Zorn ist vorüber - für heute.","atkmod"=>$attkmod,"roundmsg"=>"`\$Ramius ist überaus zornig - und lässt es Dich körperlich spüren!`i");					
				}else{
					switch(e_rand(1,10)){
	        			case 1:
	            		case 2:
	            		case 3:
	            		case 4:
	            		case 5:
		            		output("`\$`nAls Dein Herr, Ramius, heute morgen von Deinem guten Ruf erfuhr, überlegte er, ob "
								  ."er Dich motivieren oder tadeln sollte ... und entschied sich fürs ");
		            		output("Motivieren.`n`\$Seine Gnade ist heute mit Dir - und Du erhältst 2 zusätzliche Waldkämpfe!`n");
		            		$turns=get_module_setting("ramius2_turn");
		            		$hpbonus=get_module_setting("ramius2_hp");
		            		$rounds=get_module_setting("ramius2_rounds");
		            		$attkmod=get_module_setting("ramius2_attkmod");
		            		$session['user']['turns']+=$turns;
			   				$session['user']['hitpoints']*=$hpbonus;
							$session['bufflist']['Ramius2'] = array("name"=>"`\$Ramius´ Gnade","rounds"=>$rounds,"wearoff"=>"`\$Ramius hat Dir für heute genug geholfen.","atkmod"=>$attkmod,"roundmsg"=>"`\$Eine Stimme in Deinem Kopf befiehlt: `i`bZerstöre!`b Bring Leid über die Lebenden!`i");
						break;
						case 6:
						case 7:
						case 8:
						case 9:
						case 10:
		            		output("`\$`nAls Dein Herr, Ramius, heute morgen von Deinem guten Ruf erfuhr, überlegte er, ob "
								  ."er Dich motivieren oder tadeln sollte ... und entschied sich fürs ");
		            		output("Tadeln.`n`\$Sein Zorn ist heute mit Dir - und Du verlierst 2 Waldkämpfe!`n");
		            		$turns=get_module_setting("ramius3_turn");
		            		$hpbonus=get_module_setting("ramius3_hp");
		            		$rounds=get_module_setting("ramius3_rounds");
		            		$defmod=get_module_setting("ramius3_defmod");
		   		    		$session['user']['turns']-=$turns;
			   				$session['user']['hitpoints']*=$hpbonus;
							$session['bufflist']['Ramius3'] = array("name"=>"`\$Ramius´ Zorn","rounds"=>$rounds,"wearoff"=>"`\$Ramius´ Zorn ist vorüber - für heute.","defmod"=>$defmod,"roundmsg"=>"`\$Ramius ist zornig auf Dich.");
						break;
					}
				}
			}
		}
	}
return $args;
}

function derfremde_runevent($type){

    global $session;
	$op = httpget('op');
    $from = "forest.php?";
    $session['user']['specialinc'] = "module:derfremde";


if ($session['user']['ctitle']=="`\$Ramius´ ".($session['user']['sex']?"Sklavin":"Sklave").""){

	if ($op=="" || $op=="search"){

   		output("`@Nach langer Zeit findest Du zu dem Ort zurück, an dem Du damals Deine Seele an `\$Ramius`@ "
		   	  ."verkauft hast. Auf einem Baumstumpf im Sonnenschein sitzt eine Gestalt, die sich in einen schwarzen "
			  ."Umhang hüllt. Als Du nähertrittst, erhebt sie das Wort:");
   		output("`#'Mein Name ist `b`i`@Elra`i`b`#, und ich bin wie Du eine Sklavin des Ramius ...' `@Sie "
		   	  ."seufzt. `#Aber Du wandelst noch unter den Lebenden, ihm gehört nur Deine Seele. Meine Seele jedoch "
			  ."vermachte ich ihm zusammen mit meinem Körper ...'");

  		$kosten=5;
  		if ($session['user']['race']=="Vampir")	$kosten=8;

		output("`n`@Die verhüllte Gestalt erhebt sich, lüftet ihre Kapuze und zum Vorschein kommt eine wunderschöne "
			  ."Elfe. `#'Nun, ich kann Dich von seinem Griff befreien und Dir Deine Seele zurückgeben. Aber dazu "
			  ."brauche ich %s Edelsteine. Ohne sie ist es auch mir nicht möglich, seinen Fluch zu brechen.'", $kosten);

  
		if ($session['user']['gems']<$kosten){
  			output("`@`n`nSie seufzt, als sie Deinen geöffneten Beutel erblickt. `#'Wie ich sehe, hast Du nicht "
				  ."genügend Edelsteine dabei ...");
			if ($session['user']['race']=="Vampir") output("Gerade bei Vampiren ist die Prozedur sehr aufwändig, da sie von ihm erschaffen wurden ...");
  			output("Komm später wieder ...' `n`n`@Mit diesen Worten verschwindet sie zwischen den Bäumen.");
   			$session['user']['specialinc']="";
		}else{
			output("`@Sie lächelt Dich an, als sie Deinen geöffneten Beutel erblickt. `#'Wie ich sehe, hast Du "
				  ."einige dabei. `n`nMöchtest Du, dass ich `\$Ramius'`# Fluch breche?'");
    		output("`n`n`@<a href='forest.php?op=befreienja'>Ja, bitte ...</a>", true);
    		output("`n`n`@<a href='forest.php?op=befreiennein'>Nein, danke!</a>", true);
    		addnav("", $from . "op=befreienja");
    		addnav("", $from . "op=befreiennein");
    		addnav("Ja, bitte ...", $from . "op=befreienja");
    		addnav("Nein, danke!", $from . "op=befreiennein");
		}
	}

	if ($op=="befreiennein"){

		output("`@Sie seufzt. `#'Wie ich sehe, hat er Dich fest im Griff ...' `n`n`@Mit diesen Worten verschwindet "
			  ."sie zwischen den Bäumen.");
		$session['user']['specialinc']="";

	}elseif ($op=="befreienja"){

		output("`@Ohne ein weiteres Wort zu verlieren tritt `i`@Elra`i`@ an Dich heran und nimmt die "
			  ."Edelsteine entgegen. `#'Schließe nun die Augen.' `@Du tust, wie Dir geheißen und tauchst ein in "
			  ."eine Welle blaugleißenden Lichtes ... schwimmst hindurch und siehst eine Siedlung in der Ferne, "
			  ."durchleuchtet von Blau und Weiß ... `#'Das ist Chadyll'`@, sagt `i`@Elra`i`@, `#'meine Heimat, "
			  ."zu der ich nie mehr zurückkehren darf ...'`@, aber es ist, als wäre `i`@Elra`i`@ ganz weit von "
			  ."Dir entfernt ... ganz ... weit ...`n`nAls Du wieder zu dir kommst, liegst Du unter einem Baum ins "
			  ."Moos gebettet. Es bleibt nur eine Erinnerung, ein letztes Wort: `#'Wir vergessen nun ...'`n`n`@Wer "
			  ."hat das gesagt? Was hat es zu bedeuten ...?`n`n");
		output ("`^Du wurdest von `\$Ramius'`^ Fluch befreit und bekommst Deinen regulären Titel zurück! Solltest "
			  ."Du vor der Versklavung einen selbstgewählten Titel gehabt haben, so wirst Du ihn neu erstellen "
			  ."müssen.`n`n `\$'Oder hast Du etwa wirklich gedacht, so glimpflich davon kommen zu könnehehehehehe"
			  ."hahahahahahahihihahaha ...!'");

  		$kosten=5;
		if ($session['user']['race']=="Vampir") $kosten=10;

		$titel = "";
		$neu = change_player_ctitle($titel);
		$session['user']['ctitle'] = $titel;
		$session['user']['name'] = $neu;

  		$session['user']['gems']-=$kosten;
  		addnav("Zurück zum Wald.","forest.php");
  		addnews("`@%s `2begegnete `i`@Elra`i`2 und wurde mit ihrer Hilfe von %s Dasein als %s des `\$Ramius`2 befreit!", $session['user']['login'], translate($session['user']['sex']?"ihrem":"seinem"), translate($session['user']['sex']?"Sklavin":"Sklave"));
  		$session['user']['specialinc']="";
	}

}elseif ($op=="" || $op=="search"){

    output("`@Die letzte Stunde verlief sehr beschwerlich; scharfer Wind war aufgekommen und Du fragst Dich, wie "
		  ."das überhaupt sein kann, bei dem dichten Baumstand. In diesem Teil des Waldes ist es so dunkel, dass "
		  ."man kaum zwanzig Fuß weit sehen kann. Und jetzt hat es auch noch angefangen zu regnen ... Du bist "
		  ."völlig durchnässt. Hoffentlich holst Du Dir keinen Schnupfen, das wäre das letzte, was-- Jemand steht "
		  ."hinter Dir, Du spürst es ganz genau!`n`@Vorsichtig, auf Dein/e/en `b`2%s`b`@ vertrauend drehst Du Dich "
		  ."um, eine Eiseskälte im Nacken, und bereit, Dich sofort auf den Fremden zu stürzen. Doch als Du Dich "
		  ."umgedreht hast, kannst Du tief durchatmen. Da ist niemand.`nMit einem Lächeln auf den Wangen drehst Du "
		  ."Dich zurück in Deine Reiserichtung - und starrst erstarrt in die endlose Dunkelheit unter der Kapuze "
		  ."eines Mannes ... Wesens ..., das Dir, kaum eine Schwertlänge entfernt, gegenübersteht; still, stumm, in "
		  ."eine tiefschwarze Robe gehüllt, die den Boden kaum berührt - es ist, als würde der Fremde schweben. "
		  ."Langsam erhebt er seinen rechten, ausgestreckten Arm. Du kannst seine Hand nicht erkennen - aber unter "
		  ."dem langen, weiten Ärmel siehst Du etwas rotglühend hervorglitzern ... `n`nWas wirst Du tun?", $session['user']['weapon']);

	if ($session[user][race]==Vanthira) output("`n`n`@<a href='forest.php?op=gruss'>`#'`\$Ramius`#, Herr der Unterwelt, sei gegrüßt!'</a>", true);
	    output("`n`n`@<a href='forest.php?op=wegrennen'>Wegrennen!</a>", true);
	    output("`@`n`n<a href='forest.php?op=hand'>Ebenfalls die Hand ausstrecken.</a>", true);
	    output("`@`n`n<a href='forest.php?op=respekt'>Ich verlange den mir gebührenden Respekt von diesem Landstreicher!</a>", true);
	if ($session[user][race]!=Vanthira) output("`n`n`@<a href='forest.php?op=demut'>Auf die Knie! Das muss ein Gott sein!</a>", true);
	    output("`n`n`@<a href='forest.php?op=angriff'>Angreifen! Das muss ein Dämon sein!</a>", true);
	    output("`n`n`@<a href='forest.php?op=ignorieren'>Ignorieren! Das kann nur Einbildung sein!</a>", true);
	if ($session[user][race]==Vanthira)    addnav("","forest.php?op=gruss");
	    addnav("","forest.php?op=wegrennen");
	    addnav("","forest.php?op=hand");
	    addnav("","forest.php?op=respekt");
	if ($session[user][race]!=Vanthira)    addnav("","forest.php?op=demut");
	    addnav("","forest.php?op=angriff");
	    addnav("","forest.php?op=ignorieren");
	if ($session[user][race]==Vanthira)    addnav("Grüßen.","forest.php?op=gruss");
	    addnav("Wegrennen.","forest.php?op=wegrennen");
	    addnav("Hand ausstrecken.","forest.php?op=hand");
	    addnav("Respekt verlangen.","forest.php?op=respekt");
	if ($session[user][race]!=Vanthira)    addnav("Auf die Knie.","forest.php?op=demut");
	    addnav("Angreifen.","forest.php?op=angriff");
	    addnav("Ignorieren.","forest.php?op=ignorieren");

}elseif ($op=="wegrennen"){

    output("`@Wie sagte bereits Deine Großmutter? `#'Wenn Du nicht weißt, was es ist, dann lass es auf dem Teller!'"
		  ."`n`@ Du rennst so schnell Du kannst, ohne Dich umzudrehen - und merkst mit jedem Schritt, wie die "
		  ."Eiseskälte näher kommt. Links, rechts, vor Dir! Der Fremde ist überall!`n Vom Laufen erschöpft - so "
		  ."erklärst Du es später zumindest; Angst kann ja kaum der Grund gewesen sein ... -, fällst Du in Ohnmacht.");
    output("`@Was auch immer es war, es hat Dich allein durch seinen Anblick besiegt. Soviel steht fest.");

	if ($session ['user']['dragonkills']<=4) output("`@`n`nAber für `b%s %s`b`@ hast Du Dich angemessen verhalten.", translate($session['user']['sex']?"eine schwächliche":"einen schwächlichen"), $session['user']['title']);
	if ($session ['user']['dragonkills']>=5 && $session ['user']['dragonkills']<=8){
		output("`@`n`nWar eine solche Vorstellung für `b%s %s`b`@ wirklich nötig?", translate($session['user']['sex']?"eine abenteuerhungrige":"einen abenteuerhungrigen"), $session['user']['title']);
		//Feigheit ist böse. So ;)
    	if (is_module_active('alignment')) align("-1");
	}
	if ($session ['user']['dragonkills']>=9 && $session ['user']['dragonkills']<=13){
		output("`@`n`n`bFür %s %s`b`@ war das `beine äußerst schwache Vorstellung`b`@!", translate($session['user']['sex']?"eine erfahrene":"einen erfahrenen"), $session['user']['title']);
    	if (is_module_active('alignment')) align("-3");
     	addnav("Zurück zum Wald.","forest.php");
	 	addnews("`\$%s`4 verstrickte sich in Lügengeschichten über %s Feigheit!", $session['user']['name'], translate($session['user']['sex']?"ihre":"seine"));
		$body = sprintf_translate("/me `\$hört einige kleine Bauernjungen lachen und fragt sich, ob das mit %s Feigheit zu tun haben könnte ...", $session['user']['sex']?"ihrer":"seiner");
		injectcommentary("village", "","$body", $schema=false);
	}
	if ($session ['user']['dragonkills']>=14){
		output("`@`n`n`bFür %s %s`b`@ war dieses Verhalten `babsolut erniedrigend und ehrlos`b`@!", translate($session['user']['sex']?"eine gestandene":"einen gestandenen"), $session['user']['title']);
    	if (is_module_active('alignment')) align("-5");
		addnav("Zurück zum Wald.","forest.php");
		addnews("`\$%s`4 verstrickte sich in Lügengeschichten über %s Feigheit, was %s Ansehen im Land sehr schadet!",$session['user']['name'], translate($session['user']['sex']?"ihre":"seine"), translate($session['user']['sex']?"ihrem":"seinem"));
		$body = sprintf_translate("/me `\$wird von allen Anwesenden wegen %s Feigheit ausgelacht, als %s den Platz betritt.", $session['user']['sex']?"ihrer":"seiner", $session['user']['sex']?"sie":"er");
		injectcommentary("village", "","$body", $schema=false);
	}

    $turns = (e_rand(0,2));
	if ($turns==0){
    	$session['user']['turns']-=$turns;
    	$session['user']['specialinc']="";
	}else{
    	output("`n`n`@Als Du aus Deiner Ohnmacht erwachst, hast Du `^%s`@ %s verschlafen!", $turns, translate($turns==1?"Waldkampf":"Waldkämpfe"));
    	$session['user']['turns']-=$turns;
    	$session['user']['specialinc']="";
	}

}elseif ($op=="hand"){

	output("`@Dein Herz rast und Deine Finger zittern, als Du Deinen Arm ausstreckst und sich Deine Hand dem "
		  ."Glitzern unter dem Ärmel des Fremden nähert. Mit jedem weiteren Zentimeter wird es immer kälter ...");
    output("`n`n`@<a href='forest.php?op=handweiter'>Weiter.</a>", true);
    addnav("", $from . "op=handweiter");
    addnav("Weiter.", $from . "op=handweiter");

}elseif ($op=="handweiter"){

	switch(e_rand(1,10)){
		case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        output("`@Als Du das Glitzern fast erreicht hast, schließt Du die Augen. Es fühlt sich kalt an ... und "
			  ."hart. Du bleibst noch eine Weile so stehen und wagst es nicht, die Augen wieder zu öffnen. Schon "
			  ."bald hat der Gegenstand in Deiner Hand Deine Körperwärme angenommen. Du öffnest die Augen und "
			  ."siehst: `^einen Edelstein`@!`@`nVon dem Fremden ist nichts mehr zu sehen und der Regen hat sich gelegt.");
        $session['user']['gems']++;
        $session['user']['specialinc']="";
        break;
        case 6:
        case 7:
        output("`@Gebannt starrst Du auf das rote Glitzern - wie ist es wunderschön ... wie ist es ... kalt ... wie "
			  ."ist es- Völlig unvorbereitet schnellt aus dem Ärmel des Fremden eine glühende Sichel hervor und "
			  ."drückt sich in Deine offene Handfläche. Der Schmerz ist kurz und intensiv. Dir schwinden die Sinne "
			  ."...`@`nAls Du wieder aufwachst, fühlst Du Dich ausgelaugt und schwach. Der Regen hat aufgehört und "
			  ."der Fremde ist nirgends zu erblicken.");

		if ($session['user']['maxhitpoints']>$session['user']['level']*10){
        	output("`@`n`nDu verlierst `\$1`@ permanenten Lebenspunkt!");
            $session['user']['maxhitpoints']--;
            $session['user']['hitpoints']--;
		}

        output("`@`n`nDu verlierst `^1`@ Waldkampf!");
        $session['user']['turns']--;
        $session['user']['specialinc']="";
        break;
        case 8:
        case 9:
        case 10:
        output("`@Gebannt starrst Du auf das rote Glitzern - wie ist es wunderschön ... wie ist es ... kalt ... wie "
			  ."ist es- Völlig unvorbereitet schnellt aus dem Ärmel des Fremden eine Hand hervor, zart und "
			  ."ebenmäßig wie die einer jungen Frau. Das Glitzern entpuppt sich als Fingerring.`#`n'Du solltest "
			  ."nicht hier sein, %s`#'`@, hörst Du eine sanfte Stimme sagen. In demselben Moment erkennst Du unter "
			  ."der Kapuze die Züge einer jungen, bildhübschen Elfe. `#'Und auch ich nicht.' `@Sie seufzt. `#'Mein "
			  ."Name ist `i`@Elra`i`@ - `i`@Elra`i`#, die Vergessene, die Vergebliche, die Vergangene ... "
			  ."Einst zog ich das Reich der Schatten dem der Lebenden vor - um den Preis meines Glücks, um den "
			  ."Preis der Liebe, um den Preis meines geliebten Clouds ... Nimm Dich vor`$ Ramius`# in Acht, hüte "
			  ."Dich vor seinen falschen Versprechungen! Hier, nimm einen Teil meiner einstigen, weltlichen "
			  ."Schönheit - und werde mit jemandem glücklich! So, wie ich niemals mehr glücklich werden darf ...'"
			  ."`n`@Mit diesen Worten verschwindet sie in die Dunkelheit.", $session['user']['name']);
        output("`@`n`nDu erhältst `^2`@ Charmepunkte!");
        output("`@`n`nDu verlierst `\$1`@ Waldkampf!");
        $session['user']['charm']+=2;
        $session['user']['turns']-=1;
        $session['user']['specialinc']="";
        break;
	}

}elseif ($op=="respekt"){

	$sex = translate_inline($session['user']['sex']?"die":"der");
	$name = $session['user']['name'];

    output("`@Du nimmst Deine gewohnte Pose ein, die Du jeden Tag vor dem Spiegel übst, und stellst Dich nach einem "
		  ."kurzen Räuspern mit diesen Worten vor: `#'Sei Er gegrüßt, Lumpenträger!");
	if ($session ['user']['dragonkills']==0) output("`bIch bin %s überaus mutige %s`b!", $sex, $name);
	if ($session ['user']['dragonkills']>=1 && $session ['user']['dragonkills']<=4) output("`bIch bin %s überaus mutige und starke %s`b!", $sex, $name);
	if ($session ['user']['dragonkills']>=5 && $session ['user']['dragonkills']<=8) output("`bIch bin %s überaus reiche und unglaublich mutige %s`b!", $sex, $name);
	if ($session ['user']['dragonkills']>=9 && $session ['user']['dragonkills']<=13) output("`bIch bin %s allseits bekannte und überaus erfahrene %s`b!", $sex, $name);
	if ($session ['user']['dragonkills']>=14 && $session ['user']['dragonkills']<=17) output("`bIch bin %s überaus kriegserfahrene und hochdekorierte %s`b!", $sex, $name);
	if ($session ['user']['dragonkills']>=18 && $session ['user']['dragonkills']<=22) output("`bIch bin %s überaus einflussreiche und unglaublich wohlhabende %s`b!", $sex, $name);
	if ($session ['user']['dragonkills']>=23 && $session ['user']['dragonkills']<=27) output("`bIch bin %s über alle Maßen fähige und weitbekannte %s`b!", $sex, $name);
	if ($session ['user']['dragonkills']>=28 && $session ['user']['dragonkills']<=34) output("`bIch bin %s unaufhaltsame und weltberühmte %s`b!", $sex, $name);
	if ($session ['user']['dragonkills']>=35 && $session ['user']['dragonkills']<=38) output("`bIch bin %s königliche und ehrfurchtgebietende, den Göttern nahestehende %s!`b", $sex, $name);
	if ($session ['user']['dragonkills']>=39 && $session ['user']['dragonkills']<=45) output("`bIch bin %s strahlende und unglaublich mächtige %s`b!", $sex, $name);
	if ($session ['user']['dragonkills']>=46 && $session ['user']['dragonkills']<=49) output("`bIch bin %s den Göttern am nächsten kommende %s`b!", $sex, $name);
	if ($session ['user']['dragonkills']>=50) output("`bIch bin %s gottgleiche und allesvermögende %s`b!", $sex, $name);

    output("`#Sage `bEr`b mir nun, wer `bEr`b ist, dass `bEr`b es wagt, `bmich`b so zu erschrecken!'`@`nFür einen "
		  ."Moment wird es still im Wald. Es regnet noch immer, aber selbst das Plätschern ist verstummt. Der "
		  ."Fremde nimmt seinen Arm zurück und rührt sich nicht ...");
    output("`n`n`@<a href='forest.php?op=respektweiter'>Weiter.</a>", true);
    addnav("", $from . "op=respektweiter");
    addnav("Weiter.", $from . "op=respektweiter");

}elseif ($op=="respektweiter"){

	switch(e_rand(1,10)){
        case 1:
        case 2:
        case 3:
		output("`@Schließlich antwortet der Fremde mit einer tiefen, gravitätischen Stimme: `\$'Damit bist Du heute "
			  ."schon %s zweite, %s beschränkten Fähigkeiten zu Kopf gestiegen sind. - %s`\$, ich gebe Dir etwas "
			  ."Überirdisches mit auf den Weg: Überirdische Schmerzen!'", translate($session['user']['sex']?"die":"der"), translate($session['user']['sex']?"der ihre":"dem seine"), $session['user']['name']);
        output("`\$`n`nDu bist tot!");
        output("`n`n`@Du verlierst `\$%s `@Erfahrungspunkte!", round($session['user']['experience']*0.08));
        output("`n`nDu verlierst all Dein Gold!");
        output("`n`n`@Du kannst morgen weiterspielen.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.92);
        addnav("Tägliche News","news.php");
        addnews("`\$Ramius`4 gewährte `\$%s`4 Einblicke in die facettenreiche Welt unendlicher Schmerzen.",$session['user']['name']);
		$body = sprintf_translate("/me `\$hängt kopfüber in einem Dornenstrauch, wo %s von einem Peindämon genüsslich ausgelöffelt wird.", $session['user']['sex']?"sie":"er");
		injectcommentary("shade", "","$body", $schema=false);
        $session['user']['specialinc']="";
        break;
		case 4:
        case 5:
        case 6:
        output("`@Schließlich antwortet der Fremde mit einer tiefen, gravitätischen Stimme: `\$'Wie gut, dass Du "
			  ."Dich von selbst vorgestellt hast. - So weiß ich wenigstens schon mal, wie ich Dich für den Rest der "
			  ."Ewigkeit rufen werde: `b%s`\$, die kleine, dumme, völlig durchgedrehte und überhebliche Bauerngöre`b!'", $session['user']['name']);
        output("`$`n`nDu bist tot!");
        output("`n`n`@Du verlierst `$%s `@Erfahrungspunkte!", round($session['user']['experience']*0.07));
        output("`n`nDu verlierst all Dein Gold!");
        output("`n`n`@Du kannst morgen weiterspielen.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.93);
        addnav("Tägliche News","news.php");
        addnews("`4Aus dem Totenreich berichtet man, dass `\$Ramius `\$%s `\$`i'Du kleine, dumme, völlig durchgedrehte und überhebliche Bauerngöre!'`i `4nachgerufen hat!",$session['user']['name']);
		injectcommentary("shade", "","/me `\$wird von Ramius als ´kleine, dumme, völlig durchgedrehte und überhebliche Bauerngöre´ beschimpft!", $schema=false);
        $session['user']['specialinc']="";
        break;
        case 7:
        case 8:
        output("`@Schließlich antwortet der Fremde mit einer tiefen, gravitätischen Stimme:`$ 'Deine "
			  ."Überheblichkeit wird viel Verderben über die anderen Lebenden bringen. Deshalb lasse ich Dich "
			  ."ziehen. Aber nicht, ohne Dich zuvor `bnoch`b verderbenbringender gemacht zu haben!'");
        output("`@Unter der Berührung des Fremden sackst Du zusammen. Als Du wieder aufwachst, hat der Regen aufgehört.");
        output("`@`n`nDu erhältst `^1`@ Angriffspunkt!");
        output("`@`n`nDu verlierst `\$1`@ Waldkampf!");
        $session['user']['turns']--;
        $session['user']['attack']++;
        $session['user']['specialinc']="";
        break;
        case 9:
        case 10:
        output("`@Schließlich antwortet der Fremde mit einer tiefen, gravitätischen Stimme: `$'Deine "
			  ."Überheblichkeit wird viel Verderben über die anderen Lebenden bringen. Deshalb lasse ich Dich "
			  ."ziehen. Aber nicht, ohne Dich zuvor noch verderbenbringender gemacht zu haben!'");
        output("`@Unter der Berührung des Fremden sackst Du zusammen. Als Du wieder aufwachst, hat der Regen aufgehört.");
        output("`@`n`nDu verlierst die meisten Deiner Lebenspunkte!");
        output("`@`n`nDu erhältst `^2`@ permanente Lebenspunkte!");
        output("`@`n`nDu verlierst `\$1`@ Waldkampf!");
        $session['user']['maxhitpoints']+=2;
        $session['user']['hitpoints']=1;
        $session['user']['turns']--;
        $session['user']['specialinc']="";
        break;
	}

}elseif ($op=="demut"){

	$Gott1 = get_module_setting("Gott_1");
	$Gott2 = get_module_setting("Gott_2");
	$Gott3 = get_module_setting("Gott_3");
	$Gott4 = get_module_setting("Gott_4");
	$Gott5 = get_module_setting("Gott_5");
	$Gott6 = get_module_setting("Gott_6");
	$Gott7 = get_module_setting("Gott_7");
	$Gott8 = get_module_setting("Gott_8");
	$Gott9 = get_module_setting("Gott_9");
	$Gott10 = get_module_setting("Gott_10");

	output("`@Voll Ehrfurcht lässt Du Dich zu Boden sinken, hinab in den nassen Matsch.`n`n `#'`bIch bin unwürdig!"
		  ."`b' `@rufst Du. `#'`bIch bin glanzlos im Lichte Deiner Erscheinung, oh ");

  	if ($session['user']['race']==$Gott1){
      	$Anpreisung1 = get_module_setting("Anpreisung_1");
	  	output("`#$Anpreisung1`b'");
	}elseif ($session['user']['race']==$Gott2){
	  	$Anpreisung2 = get_module_setting("Anpreisung_2");
	  	output("`#$Anpreisung2`b'");
	}elseif ($session['user']['race']==$Gott3){
	  	$Anpreisung3 = get_module_setting("Anpreisung_3");
	  	output("`#$Anpreisung3`b'");
	}elseif ($session['user']['race']==$Gott4){
	  	$Anpreisung4 = get_module_setting("Anpreisung_4");
	  	output("`#$Anpreisung4`b'");
	}elseif ($session['user']['race']==$Gott5){
	  	$Anpreisung5 = get_module_setting("Anpreisung_5");
	  	output("`#$Anpreisung5`b'");
	}elseif ($session['user']['race']==$Gott6){
	  	$Anpreisung6 = get_module_setting("Anpreisung_6");
	  	output("`#$Anpreisung6`b'");
	}elseif ($session['user']['race']==$Gott7){
	  	$Anpreisung7 = get_module_setting("Anpreisung_7");
	  	output("`#$Anpreisung7`b'");
	}elseif ($session['user']['race']==$Gott8){
	  	$Anpreisung8 = get_module_setting("Anpreisung_8");
	  	output("`#$Anpreisung8`b'");
	}elseif ($session['user']['race']==$Gott9){
	  	$Anpreisung9 = get_module_setting("Anpreisung_9");
	  	output("`#$Anpreisung9`b'");
	}elseif ($session['user']['race']==$Gott10){
	  $Anpreisung10 = get_module_setting("Anpreisung_10");
	  output("`#$Anpreisung10`b'");
	}

    output("`@`n`nZitternd wartest Du auf eine Reaktion.");
    output("`n`n`@<a href='forest.php?op=demutweiter'>Weiter.</a>", true);
    addnav("", $from . "op=demutweiter");
    addnav("Weiter.", $from . "op=demutweiter");

}elseif ($op=="demutweiter"){

	if ($session['user']['race']=="Vampir"){
    	output("`@Schließlich antwortet der Fremde mit einer tiefen, gravitätischen Stimme:`n `$'So ist es recht, "
			  ."Kind der Nacht - Geschöpf `imeiner`i Allmacht! Du hast zu mir gefunden, Deinem Meister ... Sag, "
			  ."willst Du mir noch näher kommen, indem Du Dich zu %s machst?'", translate($session['user']['sex']?"meiner Sklavin":"meinem Sklaven"));
    	output("<a href='forest.php?op=sklave'>`n`nJa, mein Erschaffer, ich möchte Deine unvergleichliche Macht aus nächster Nähe spüren! Mache mich zu %s!</a>", translate($session['user']['sex']?"Deiner Sklavin":"Deinem Sklaven"), true);
    	output("<a href='forest.php?op=ablehnung'>`n`nOh großartiger Erschaffer, eines solchen Geschenks bin ich nicht würdig ...</a>", true);
    	addnav("", $from . "op=sklave");
    	addnav("", $from . "op=ablehnung");
    	addnav("Annehmen.", $from . "op=sklave");
    	addnav("Ablehnen.", $from . "op=ablehnung");
	}else{
		switch(e_rand(1,10)){
        	case 1:
            case 2:
            output("`@`#'Erhebe Dich, Sterblicher!'`@ hörst Du eine sanfte Stimme sagen. Du tust, wie Dir geheißen "
				  ."und erblickst unter der Kapuze das Antlitz einer jungen, bildhübschen Elfe. `#'Ich bin kein "
				  ."Gott und auch keine Göttin. Wisse, dass ich `i`@Elra`i`@ bin, die Verblendete und ewige "
				  ."Gefangene des `\$Ramius`#. Verschwinde von hier, schnell! Er ist hier, in mir - und ich kann "
				  ."ihn nur für kurze Zeit zurückhalten. - Nimm das, auf dass es Dich auf Deinen Abenteuern beschütze.'");
            output("`n`@Du greifst nach dem Fingerring, den sie Dir hinhält, verbeugst Dich und rennst davon.`n"
				  ."Schon bald hat der Regen aufgehört und Du kannst verschnaufen. Sie hat Dir einen Schutzring der "
				  ."Lichtelfen gegeben!");
            output("`n`n`@Du erhältst `^1`@ Punkte Verteidigung!");
            output("`n`nDu verlierst einen Waldkampf!");
            $session['user']['turns']--;
            $session['user']['defense']++;
            $session['user']['specialinc']="";
            break;
            case 3:
            case 4: 
            case 5:
            case 6: 
            output("`@Schließlich antwortet der Fremde mit einer tiefen, gravitätischen Stimme: `$'Das ist ja "
				  ."geradezu `berbärmlich`b! Erst dieser arrogante Schwächling von eben - und nun so etwas! "
				  ."Verschwinde! Für Dich ist noch der Tod zu schade!'");
            output("`n`@Du rutscht ein paar Mal aus, als Du im regennassen Schlamm aufstehen willst, und rennst so "
				  ."schnell Du kannst davon. Wer auch immer der Fremde war, er hatte gerade ziemlich schlechte Laune ...");
            output("`n`n`@Du verlierst einen Waldkampf!");
            $session['user']['turns']--;
            $session['user']['specialinc']="";
            break;
            case 7:
            case 8:
            case 9:
            case 10:
            output("`@Schließlich antwortet der Fremde mit einer tiefen, gravitätischen Stimme: `$'So ist es recht! "
				  ."Nieder in den Schlamm mit Dir, erbärmlicher Sterblicher! Ich sehe, Du hast bei Deinen "
				  ."Aufenthalten in meinem Reich viel gelernt, nur die korrekte Anpreisung meiner Herrlichkeit "
				  ."müssen wir noch üben. Erinnere mich beim nächsten Mal daran, dass Du ein paar Gefallen gut hast ...'");
            output("`@Während Du zitternd daliegst, löst sich der Fremde in der Dunkelheit auf.");
            $gefallen1 = e_rand(50,250);
            $session['user']['deathpower']+=$gefallen1;
            output("`n`nDu erhältst `^%s`@ Gefallen von`$ Ramius`@!", $gefallen1);
            output("`n`nDu verlierst einen Waldkampf!");
            $session['user']['turns']--;
            $session['user']['specialinc']="";
            break;
		}
	}

}elseif ($op=="angriff"){

	output("`@Geistesgegenwärtig springst Du mit einem Satz zurück und bringst Dein/e/en `b%s`b in Bereitschaft."
		  ."`n`#'Kreatur der Niederhöllen'`@, rufst Du,`# 'Dein letztes Stündlein hat geschlagen!'", $session['user']['weapon']);
  	output("`n`n`@<a href='forest.php?op=angriffweiter1'>Weiter.</a>", true);
    addnav("", $from . "op=angriffweiter1");
    addnav("Weiter.", $from . "op=angriffweiter1");

}elseif ($op=="angriffweiter1"){

	switch(e_rand(1,10)){
    	case 1:
        case 2:
        case 3:
        case 4:
        output("`@`#'Warte, Fremder!'`@ - Die Gestalt lüftet ihre Kapuze und zum Vorschein kommt eine bildhübsche "
			  ."Elfe. Sie wirkt traurig. `#'Hat der Tod mich etwa dermaßen verändert, dass man mich für einen "
			  ."Dämonen halten kann?! Ach ... lass gut sein ...'`n`n `@Die Fremde verschwindet in der Dunkelheit. "
			  ."Wer sie wohl war?");
        $session['user']['specialinc']="";
        break;
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        output("`@Du willst gerade entschlossen vorstürmen, als Dich plötzlich ein kalter Griff im Nacken festhält "
			  ."und einen Fingerbreit anhebt. Unter der Kapuze dröhnt eine dunkle Stimme hervor: `n`$'Glaubst Du "
			  ."`bwirklich`b, dass `bDu`b es mit mir aufnehmen kannst, Sterblicher?'");
        output("`n`n`@<a href='forest.php?op=angriffweiter2'>Ja, Bestie!</a>", true);
        addnav("", $from . "op=angriffweiter2");
        addnav("Ja.", $from . "op=angriffweiter2");
        output("`n`n`@<a href='forest.php?op=angriffweiter3'>Also, eigentlich ...</a>", true);
        addnav("", $from . "op=angriffweiter3");
        addnav("Nein.", $from . "op=angriffweiter3");
	}

}elseif ($op=="angriffweiter2"){

	switch(e_rand(1,10)){
    	case 1:
		case 2:
		case 3:
        case 4:
        case 5:
        output("`$'Ha! Ist es Leichtsinn oder ist es Mut? In jedem Fall wäre es eine große Dummheit! Du kannst Dich "
			  ."glücklich schätzen, dass mir gerade nicht danach ist, Dich ganz mitzunehmen ...'`@`n Die eisige "
			  ."Hand in Deinem Nacken schleudert Dich weitab in die Büsche. Als Du wieder aufwachst, hat der Regen "
			  ."aufgehört und der Fremde ist verschwunden.");
		$session['user']['hitpoints']=1;
        output("`n`n`@Du verlierst fast alle Deine Lebenspunkte!");
        output("`n`n`@Du verlierst `^1`@ Waldkampf!");
        $session['user']['turns']--;
		if ($session['user']['maxhitpoints']>$session['user']['level']*10){
        	output("`@`n`nDu verlierst `\$1`@ permanenten Lebenspunkt!");
        	$session['user']['maxhitpoints']--;
		}
		$session['user']['specialinc']="";
		break;
		case 6:
		case 7:
        output("`@`$'Dann zeig, was Du kannst!'`n`@Das lässt Du Dir nicht zweimal sagen. Sobald sich der Griff "
			  ."gelockert hat, stürmst Du mit einem wilden, furchterregenden Schrei nach vorne, holst aus und - "
			  ."schlägst durch den Fremden hindurch! `@`nVon Deinem eigenen Schwung umgerissen, fällst Du zu Boden. "
			  ."Als Du wieder aufschaust, stellst Du mit Schrecken fest, dass der Fremde sich über Dich gebeugt "
			  ."hat. Das letzte, was Du spürst, ist ein seltsames Stechen an der Stirn ... Dein Tod muss grauenvoll "
			  ."gewesen sein.");
        output("`$`n`nDu bist tot!");
		if ($session['user']['maxhitpoints']>$session['user']['level']*10){
        	$hpverlust = e_rand(1,3);
            output("`@`n`nDu verlierst `$%s`@ permanente(n) Lebenspunkt(e)!", $hpverlust);
            $session['user']['maxhitpoints']-=$hpverlust;
            $session['user']['hitpoints']-=$hpverlust;
		}else{
            output("`n`n`@Du verlierst `$%s`@ Erfahrungspunkte und all Dein Gold!", round($session['user']['experience']*0.10));
            output("`n`n`@Du kannst morgen weiterspielen.");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['gold']=0;
            $session['user']['experience']=round($session['user']['experience']*0.90);
            addnav("Tägliche News","news.php");
            addnews("`\$Ramius `4hat `\$%s`4´s Seele durch einen Strohhalm eingesogen ...", $session['user']['name']);
		}
        $session['user']['specialinc']="";
        break;
        case 8:
		case 9:
		case 10:
        output("`@`$'Ha! Ist es Leichtsinn oder ist es Mut? In jedem Fall wäre es eine große Dummheit! Aber ich mag "
			  ."Deine Geradlinigkeit - eine seltene Tugend unter Euch Sterblichen. Dafür sollst Du belohnt werden! "
			  ."Aber zuvor begleitest Du mich noch in mein Schattenreich ...'`$`n`nDu bist tot und Ramius verwehrt "
			  ."es Dir, noch heute zu den Lebenden zurückzukehren!");
        output("`n`n`@Du verlierst `$%s`@ Erfahrungspunkte und all Dein Gold!", round($session['user']['experience']*0.15));
        output("`n`n`\$Ramius`@ gewährt Dir `^1`@ Punkt Verteidigung!");
        output("`n`n`\$Ramius`@ gewährt Dir `^1`@ Punkt Angriff!");
        output("`n`n`@Du kannst morgen weiterspielen.");
        $session['user']['alive']=false;
        $session['user']['defense']++;
		$session['user']['attack']++;
		$session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['experience']=round($session['user']['experience']*0.85);
        $session['user']['gravefights']=0;
		addnav("Tägliche News","news.php");
        addnews("`\$%s `4hat`\$ Ramius`4 tief beeindruckt und darf einen Tag lang sein Mausoleum bewachen!", $session['user']['name']);
		injectcommentary("shade", "","/me `\$hat eine große Sichel dabei und postiert sich als Wache vor dem Mausoleum!", $schema=false);
		if ($session['user']['deathpower']>=100){
        	$session['user']['deathpower']=99;
            $session['user']['specialinc']="";
		}else{
            $session['user']['specialinc']="";
		}
        break;
	}

}elseif ($op=="angriffweiter3"){

	output("`@`$'Dann nieder mit Dir in den Dreck, Du erbärmlicher, ehrloser Feigling!'`@ Du tust, wie Dir "
		  ."geheißen und wartest zitternd darauf, dass der Regen aufhört. Es vergehen Stunden in ehrloser "
		  ."Schande ... Dann erst wagst Du es wieder aufzuschauen.`n`n Der Fremde ist nirgends zu entdecken.");
    $turns2 = e_rand(2,5);
    if ($session['user']['turns']>=2) output("`n`n`^Du verlierst %s Waldkämpfe!", $turns2);
    if ($session['user']['turns']==1) output("`n`n`^Du verlierst 1 Waldkampf!");
    $session['user']['turns']-=$turns2;
    if ($session['user']['turns']<0) $session['user']['turns']=0;
            
    if (is_module_active('alignment')) align("-2");
    $session['user']['specialinc']="";

}elseif ($op=="ignorieren"){

	output("`@Du konzentrierst Dich voll und ganz auf Deinen gesunden Verstand und ...");
   	output("`n`n`@<a href='forest.php?op=ignorierenweiter'>Weiter.</a>", true);
   	addnav("", $from . "op=ignorierenweiter");
  	addnav("Weiter.", $from . "op=ignorierenweiter");

}elseif ($op=="ignorierenweiter"){

	switch(e_rand(1,10)){
        case 1:
        case 2:
        output("`@... tatsächlich! Der Fremde war nur eine Einbildung. Du kannst weiterziehen.");
        $session['user']['specialinc']="";
        break;
        case 3:
        output("`@... wirst immer unsicherer. Der Fremde schwebt vor Dir, als wäre es das Normalste der Welt.`n "
			  ."Unter seiner Kapuze dringt schließlich eine dunkle Stimme hervor: `$'Du hast großen Mut bewiesen, "
			  ."mir nicht zu weichen, %s`\$! Nimm diesen Beutel als Belohnung.'`@ Der Fremde lässt einen kleinen "
			  ."Beutel fallen, den Du sofort aufhebst. Als Du Dich wieder aufgerichtet hast, fallen gerade die "
			  ."letzten Regentropfen von den Bäumen herab. Der Fremde ist verschwunden.", $session['user']['name']);
        $gold = e_rand(500,1500);
        output("`@`n`nDu erhältst `^%s`@ Goldstücke!", round($gold*$session['user']['level']));
        output("`n`nDu verlierst `\$1`@ Waldkampf!");
        $session['user']['turns']--;
        addnav("Zurück zum Wald.","forest.php");
        addnews("`\$%s`4 wurde für %s außergewöhnliche Willensstärke von `\$Ramius`4 mit `^%s`4 Goldstücken belohnt!", $session['user']['name'], translate($session['user']['sex']?"ihre":"seine"), round($gold*$session['user']['level']));
        $session['user']['gold'] += round($gold * $session['user']['level']);
        $session['user']['specialinc']="";
        break;
        case 4:
        case 5:
        output("`@... wirst immer unsicherer. Der Fremde schwebt vor Dir, als wäre es das normalste der Welt. `n"
			  ."Unter seiner Kapuze dringt schließlich eine dunkle Stimme hervor: `$'Du wagst es, mir nicht zu "
			  ."weichen! Mir? Ramius, dem Gebieter der Toten und Schrecken der Lebenden?! Eine bodenlose Frechheit "
			  ."ist das!' `@`nJetzt geht alles ganz schnell. Der Fremde prescht nach vorne und fährt in Deinen "
			  ."Körper ein - Dir schwinden die Sinne. Als Du wieder aufwachst findest Du Dich auf dem Dorfplatz "
			  ."wieder - nackt! Aber immerhin unverletzt.");
        output("`n`n`@Du verlierst all Dein Gold!");
        output("`n`nDu verlierst `\$2`@ Waldkämpfe!");
        $session['user']['turns']-=2;
		$session['user']['gold']=0;
        addnav("Erwache auf dem Dorfplatz.","village.php");
        addnews("`7Heute herrschte großes Gelächter auf dem Dorfplatz, als `&%s`7 nackt und bewusslos neben der "
			  ."Kneipe aufgefunden wurde!", $session['user']['name']);
		injectcommentary("village", "","/me `7wird bewusstlos und splitterfasernackt neben der Kneipe aufgefunden!", $schema=false);
    	//Erniedrigung macht wütend = böse
        if (is_module_active('alignment')) align("-3");
        $session['user']['specialinc']="";
        break;
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
    	output("`@... wirst immer unsicherer. Der Fremde schwebt vor Dir, als wäre es das normalste der Welt. `n"
			  ."Unter seiner Kapuze dringt schließlich eine dunkle Stimme hervor: `$'Du hast großen Mut bewiesen, "
			  ."mir nicht zu weichen! Wisse, dass ich Ramius bin, der Gebieter über das Reich der Schatten. Als "
			  ."Belohnung für Deine unglaubliche Willenskraft gewähre ich Dir `beinen`b Wunsch.`n`n Was soll ich "
			  ."für Dich tun?'");
		if ($session['user']['race']!="Vanthira") output("`n`n<a href='forest.php?op=sklave'>Ich möchte Deine unvergleichliche Macht aus nächster Nähe spüren!`n Meister, mache mich zu %s!</a>", translate($session['user']['sex']?"Deiner Sklavin":"Deinem Sklaven"), true);
    		output("`@`n`n<a href='forest.php?op=gefallen'>Gewähre mir Gefallen im Schattenreich!</a>", true);
		if ($session['user']['race']!="Vanthira") output("`n`n`@<a href='forest.php?op=opferung'>Nimm mein Leben zum Zeichen meiner Hochachtung!</a>", true);
		if ($session['user']['race']=="Vanthira") output("`n`n`@<a href='forest.php?op=opferung'>Guter Freund, lass uns zusammen ins Schattenreich gehen.</a>", true);
    	output("`n`n`@<a href='forest.php?op=wunschlos'>Ich habe keine Wünsche.</a>", true);
		if ($session['user']['race']!="Vanthira") addnav("", $from . "op=sklave");
    	addnav("", $from . "op=gefallen");
    	addnav("", $from . "op=wunschlos");
    	addnav("", $from . "op=opferung");
    	if ($session['user']['race']!="Vanthira") addnav("Sklave werden.", $from . "op=sklave");
    	addnav("Gefallen gewähren.", $from . "op=gefallen");
    	addnav("Leben verschenken.", $from . "op=opferung");
    	addnav("Wunschlos.", $from . "op=wunschlos");
    	break;
	}

}elseif ($op=="sklave"){

	output("`$'So sei es!`n`n'Nun wirst Du bis ans Ende aller Tage %s sein! `n`nDeine Seele ist mein ... `n`nZieh "
		  ."nun aus und `bbekehre`b! Verbreite mein Wort und bringe Unheil über alle Lebenden, die es nicht hören "
		  ."wollen!`b'", translate($session['user']['sex']?"meine Sklavin":"mein Sklave"));

	$titel = "`\$Ramius´ ".($session['user']['sex']?"Sklavin":"Sklave");
	$neu = change_player_ctitle($titel);
	$session['user']['ctitle'] = $titel;
	$session['user']['name'] = $neu;

  	addnews("`\$%s`4 begegnete `\$Ramius`4 und machte sich freiwillig zu %s!",$session['user']['login'], translate($session['user']['sex']?"seiner Sklavin":"seinem Sklaven"));
  	addnav("Zurück zum Wald.","forest.php");
  	$session['user']['specialinc']="";

}elseif ($op=="gefallen"){

	$gefallen= e_rand(50,250);
  	output("`$ 'So sei es!'");
  	output("`$`n`nRamius gewährt Dir `^%s`\$ Gefallen!", $gefallen);
  	$session['user']['deathpower']+=$gefallen;
	$session['user']['specialinc']="";

}elseif ($op=="opferung"){

	if ($session['user']['race']!="Vanthira")  output("`$ 'So sei es!'");
	if ($session['user']['race']=="Vanthira")  output("`$ 'So sei es! Folge mir, Wanderer ...'");
  	output("`$`n`nDu bist tot!");
  	output("`n`n`\$Du kannst morgen weiterspielen.");
  	$session['user']['alive']=false;
  	$session['user']['hitpoints']=0;
  	$session['user']['gold']=0;
  	addnav("Tägliche News","news.php");

	if ($session['user']['race']!="Vanthira"){
  		addnews("`4Aus unerfindlichen Gründen hat `\$%s`4 %s Leben an `\$Ramius`4 verschenkt!",$session['user']['name'], translate($session['user']['sex']?"ihr":"sein"));
		$body = sprintf_translate("/me `\$kehrt heute aus freien Stücken in das Schattenreich ein - %s Leben ein Geschenk an Ramius!", translate($session['user']['sex']?"ihr":"sein"));
	}

	if ($session['user']['race']=="Vanthira"){
  		addnews("`3%s Vanthira `#%s`3 begleitete `\$Ramius`3 neugierig ins Schattenreich ...", translate($session['user']['sex']?"Die":"Der"),$session['user']['login']);
		$body = sprintf_translate("/me `\$, %s Vanthira, kehrt neugierig in das Schattenreich ein.", translate($session['user']['sex']?"eine":"ein"));
	}
	injectcommentary("shade", "","$body", $schema=false);
  	$session['user']['specialinc']="";

}

else if ($op=="wunschlos"){

    output("`$ 'Bemerkenswert! `bÄußerst`b bemerkenswert ...'");
  	if (is_module_active('alignment')) align("5");
  	addnews("`2Von `\$Ramius`2 vor die Wahl gestellt erwies sich `@%s`2 als wunschlos glücklich ...", $session['user']['name']);
  	$session['user']['specialinc']="";

}elseif ($op=="ablehnung"){

    output("`$'Wie kannst Du es wagen, meine Großzügigkeit zu verschmähen?! - Das wirst Du mir büßen ...'");
    output("`n`n`@Als Du auf dem Dorfplatz wieder zu Dir kommst, hast Du keine Erinnerung an die Begegnung mit Ramius.");
    output("`n`n`@Du verlierst all Dein Gold!");
    output("`n`nDu verlierst `\$3`@ Waldkämpfe!");
    $session['user']['turns']-=3;
	$session['user']['gold']=0;
    addnav("Komm zu Dir ...","village.php");
    addnews("`7Heute herrschte großes Erstaunen, als `&%s`7 nackt auf dem Dorfplatz herumhüpfte und sich pausenlos "
		  ."Backpfeifen verpasste!", $session['user']['name']);
	$body = sprintf_translate("/me `7hüpft nackt herum und verpasst sich Backpfeifen! Erst nach einer Viertelstunde kommt %s wieder zu sich, kann sich aber an nichts erinnern.", $session['user']['sex']?"sie":"er");
	injectcommentary("village", "","$body", $schema=false);
    if (is_module_active('alignment')) align("-5");
    $session['user']['specialinc']="";

}elseif ($op=="gruss"){

    output("`4Mit gravitätischer Stimme antwortet er: `$'Sei auch Du mir gegrüßt, Wanderer zwischen den Welten ... "
		  ."Ich hoffe, wir sehen uns bald im Schattenreich wieder ...'`n`n`@Als er in einem Schatten verschwunden "
		  ."ist, bleibst Du noch eine Weile nachdenklich stehen ... Aber jetzt ist noch nicht der richtige Moment, "
		  ."ihm zu folgen.");
    $session['user']['specialinc']="";

}
}
function derfremde_run(){
}
?>
