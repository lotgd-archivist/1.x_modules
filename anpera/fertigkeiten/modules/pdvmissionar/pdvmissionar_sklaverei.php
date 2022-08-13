<?php

function pdvmissionar_sklaverei_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Der Missionar");
	
	output("`@Der nette Mann führt Dich lächelnd in sein Zelt. Ebenso lächelt und in jeglicher Hinsicht frei kommst Du wieder heraus - ohne den blassesten Schimmer, wie er das gemacht hat.");
	addnews("`#%s`3 ließ sich von dem Missionar der Vanthira aus der Sklaverei herausführen!", $session[user][name]);
	$titel = "";
	$neu = change_player_ctitle($titel);
	$session['user']['ctitle'] = $titel;
	$session['user']['name'] = $neu;
	$session[user][gems]-=10;
	addnav("Zurück zum Platz","runmodule.php?module=wettkampf&op1=");
	addnav("","runmodule.php?module=wettkampf&op1=");
	page_footer();
}
?>