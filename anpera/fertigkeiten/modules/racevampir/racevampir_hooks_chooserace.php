<?php
	$mindk = get_module_setting("mindk");
	    if ($session['user']['dragonkills'] < $mindk){
        	output("`4Allen V�lkern sind sie bekannt, die `bKinder der Nacht`b, empfindlich gegen�ber dem Sonnenlicht und auf der t�glichen Suche nach dem Saft der Lebenden - ein Segen und ein Fluch zugleich. Wer einen Vampir spielt, sollte sich f�r das Rollenspiel aussuchen, welchem Volk er entstammt.`n`b`4[Diese Rasse steht nur Spielern zur Verf�gung, die bereits ".$mindk." Titelsteigerungen hinter sich haben.]`b`n`n", true);
		}else{
			output("<a href='newday.php?setrace=Vampir$resline'>Allen V�lkern sind sie bekannt, `4die Kinder der Nacht, empfindlich gegen�ber dem Sonnenlicht und auf der t�glichen Suche nach dem Saft der Lebenden - ein Segen und ein Fluch zugleich. Wer einen Vampir spielt, sollte sich f�r das Rollenspiel aussuchen, welchem Volk er entstammt.`n`n", true);
	        addnav("`4Vampir`0","newday.php?setrace=$race$resline");
	        addnav("","newday.php?setrace=$race$resline");
    	}
?>
