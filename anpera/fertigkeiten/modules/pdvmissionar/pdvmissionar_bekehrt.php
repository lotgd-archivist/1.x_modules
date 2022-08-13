<?php

require_once("lib/systemmail.php");
require_once("lib/commentary.php");

function pdvmissionar_bekehrt_run_private($args=false){
	global $session;
	page_header("Der Platz der Völker - Der Missionar");
	
	output("`@Der nette Mann führt Dich lächelnd in sein Zelt - und Du spürst einen dumpfen Schlag auf den Kopf. Von dem fachgerechten Schnitt an Deinem Hals, von dem Ritual, das Dich mit `\$Ramius'`@ Hilfe zu einem Vanthira werden ließ, von alldem bekommst Du nichts mit.`n`n`\$Du erwachst im Totenreich.");
	addnav("Tägliche News","news.php");
	addnews("`#%s`3 ließ sich von dem Missionar der Vanthira auf den Weg des Ausgleichs führen ...", $session[user][name]);
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