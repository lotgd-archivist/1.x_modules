<?php

function pdvmissionar_sklaverei_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Der Missionar");
	
	output("`@Der nette Mann f�hrt Dich l�chelnd in sein Zelt. Ebenso l�chelt und in jeglicher Hinsicht frei kommst Du wieder heraus - ohne den blassesten Schimmer, wie er das gemacht hat.");
	addnews("`#%s`3 lie� sich von dem Missionar der Vanthira aus der Sklaverei herausf�hren!", $session[user][name]);
	$titel = "";
	$neu = change_player_ctitle($titel);
	$session['user']['ctitle'] = $titel;
	$session['user']['name'] = $neu;
	$session[user][gems]-=10;
	addnav("Zur�ck zum Platz","runmodule.php?module=wettkampf&op1=");
	addnav("","runmodule.php?module=wettkampf&op1=");
	page_footer();
}
?>