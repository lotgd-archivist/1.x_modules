<?php

require_once("lib/systemmail.php");
require_once("lib/commentary.php");

function pdvmissionar_bekehrt_run_private($args=false){
	global $session;
	page_header("Der Platz der V�lker - Der Missionar");
	
	output("`@Der nette Mann f�hrt Dich l�chelnd in sein Zelt - und Du sp�rst einen dumpfen Schlag auf den Kopf. Von dem fachgerechten Schnitt an Deinem Hals, von dem Ritual, das Dich mit `\$Ramius'`@ Hilfe zu einem Vanthira werden lie�, von alldem bekommst Du nichts mit.`n`n`\$Du erwachst im Totenreich.");
	addnav("T�gliche News","news.php");
	addnews("`#%s`3 lie� sich von dem Missionar der Vanthira auf den Weg des Ausgleichs f�hren ...", $session[user][name]);
	$session[user][alive]=false;
	$session[user][hitpoints]=0;
		
	$race=$session[user][race];
	if ($race == "Dwarf") $race="Zwerg";
	else if ($race == "Human") $race="Mensch";
	
	injectcommentary(shade, "", "/me `@kehrte als ".$race." in das Schattenreich ein, um ein Vanthira zu werden.", $schema=false);		
	$session[user][race]="Vanthira";
	page_footer();
}
?>