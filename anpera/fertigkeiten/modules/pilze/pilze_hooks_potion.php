<?php

function pilze_hooks_dohook_potion_private($args=false){
		global $session;
		if (has_buff("pilzschlecht") || has_buff("krautschlecht")) {
			addnav("Sonstiges");
			addnav("Vergiftung heilen", "runmodule.php?module=pilze&op=healpoison");
		}
	return $args;
}
