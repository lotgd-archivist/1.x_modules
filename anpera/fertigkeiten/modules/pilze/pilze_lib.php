<?php
function pilze_buff($welcher) {	// Art, Namen übergeben?
	/*
	Pilz: stärkend, neutral, giftig
	Kraut: heilend, neutral, giftig
	Beere: stärkend, neutral, schwächend
	*/
	global $session;
	$r = e_rand(5,10);	// Runden, die die Buffs andauern
	switch ($welcher) {
	case 1:
		apply_buff("pilzgut", array(
			"name"=>"`%Pilzstärke!",
			"startmsg"=>"Du zerkaust den Pilz, er schmeckt gut und verleiht Dir gewaltige Kräfte!",
			"rounds"=>$r,
			"atkmod"=>1.1,
			"defmod"=>1.1,
			"roundmsg"=>"Der Pilz verleiht Dir Kraft!",
			"wearoff"=>"Deine Kraft läßt nach.",
			"schema"=>"pilze"
		));
		break;
	case 2:
		apply_buff("pilzneutral", array(
			"startmsg"=>"Du zerkaust den Pilz, er schmeckt nicht sonderlich gut und hat keinen Effekt.",
			"schema"=>"pilze"
		));
		break;
	case 3:
		apply_buff("pilzschlecht", array(
			"name"=>"`\$Vergiftet!",
			"startmsg"=>"Du zerkaust den Pilz, er schmeckt schrecklich bitter und Dir wird ganz schlecht.",
			"rounds"=>$r,
			"minioncount"=>1,
			"mingoodguydamage"=>round($session['user']['maxhitpoints']*0.05),
			"maxgoodguydamage"=>round($session['user']['maxhitpoints']*0.1),
			"effectmsg"=>"Du mußt dich übergeben und verlierst {damage} Lebenspunkte.",
			"wearoff"=>"Die Wirkung des Giftpilzes scheint nachzulassen.",
			"schema"=>"pilze"
		));
		break;
	case 4:
		apply_buff("krautgut", array(
			"name"=>"`^Heilkraut",
			"startmsg"=>"Du legst das Kraut auf Deine Zunge und spürst wie sofort Wärme Deinen ganzen Körper durchströmt.",
			"rounds"=>$r,
			"regen"=>floor($session['user']['maxhitpoints']/$r),
			"effectmsg"=>"Die Wirkung des Krautes heilt {damage} Lebenspunkte.",
			"schema"=>"pilze"
			
		));
		break;
	case 5:
		apply_buff("krautneutral", array(
			"startmsg"=>"Du zerkaust das Kraut, es schmeckt nicht sonderlich gut, sondern klebt Dir nur zwischen den Zähnen und hat keinen Effekt.",
			"schema"=>"pilze"
		));
		break;
	case 6:
		apply_buff("krautschlecht", array(
			"name"=>"`\$Vergiftet!",
			"startmsg"=>"Du zerkaust das Kraut, es schmeckt schrecklich bitter und Dir wird ganz schlecht.",
			"rounds"=>$r,
			"minioncount"=>1,
			"mingoodguydamage"=>round($session['user']['maxhitpoints']*0.05),
			"maxgoodguydamage"=>round($session['user']['maxhitpoints']*0.1),
			"effectmsg"=>"Du mußt Dich übergeben und verlierst {damage} Lebenspunkte.",
			"wearoff"=>"Die Wirkung des Giftes scheint nachzulassen.",
			"schema"=>"pilze"
			
		));
		break;
	case 7:
		apply_buff("beeregut", array(	// was anderes? Feuer spucken, Direktschaden?
			"name"=>"`@Beerenstärke!",	// *g*
			"startmsg"=>"Du zerkaust die Beeren, sie schmecken gut und verleihen Dir gewaltige Kräfte!",
			"rounds"=>$r,
			"atkmod"=>1.2,
			"defmod"=>1.0,
			"roundmsg"=>"Die Beeren verleihen Dir Kraft!",
			"wearoff"=>"Deine Kraft läßt nach.",
			"schema"=>"pilze"
		));
		break;
	case 8:
		apply_buff("beereneutral", array(
			"startmsg"=>"Du zerkaust die Beeren, sie schmecken nicht sonderlich gut und machen nichts, ausser Flecken an den Fingern.",
			"schema"=>"pilze"
		));
		break;
	case 9:
		apply_buff("beereschlecht", array(
			"name"=>"`\$Schwäche!",
			"startmsg"=>"Du zerkaust die Beeren, sie schmecken irgendwie komisch und es beginnt sich auch schon alles zu drehen.",
			"rounds"=>$r,
			"atkmod"=>0.7,
			"defmod"=>0.8,
			"roundmsg"=>"Uiihhh, sind das schöne Farben...",
			"wearoff"=>"Leider verschwinden die schönen Farben und Du schaust in das noch ein wenig verschwommene Gesicht von {badguy}.",
			"schema"=>"pilze"
		));
		break;
	}
	
}

function pilze_randname($art, $kat) {
			// 		Art			Kategorie	Namen
	$namen = array(	1=>	array(	1=>	array(1=>"Goliathknolle","Gelber Flinkfuß","Rinniger Stahlkopf"),
								2=>	array(1=>"Silbriger Mondschwamm","Hellgrauer Staubpilz","Braungesprengter Riesenhut"),
								3=>	array(1=>"Graugrüne Friedhofsmorchel","Flammender Schädeltäubling","Erdblättriger Rißpilz")
								),
					2=>	array(	1=>	array(1=>"Königskraut","Monarchenvierblatt","Kaiserklee"),
								2=>	array(1=>"Bittersilche","Bauernsenf","Weihrauchkraut"),
								3=>	array(1=>"Schwarzes Stechkraut","Dornenhalm","Katzentod")
								),
					3=>	array(	1=>	array(1=>"Gelbe Beerentraube","Sonnenbeere","Mondkirsche"),
								2=>	array(1=>"Mandelbeere","Drachentraube","Wiesenhäubchen"),
								3=>	array(1=>"Gallendorn","Schattenmispel","Gezwirbelte Tollbeere")
								)
	);
	$key = e_rand(1,sizeof($namen[$art][$kat]));
	$name = $namen[$art][$kat][$key];
	return $name;
}
?>
