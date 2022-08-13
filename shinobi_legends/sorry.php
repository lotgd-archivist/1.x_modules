<?php

function sorry_getmoduleinfo(){
	$info = array(
		"name"=>"Sorry",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Village Specials",
		"download"=>"",
		"prefs"=>array(
			"Offering Special User Preferences,title",
			"seen"=>"Seen special today?,bool|0",
		)
	);
	return $info;
}

function sorry_install(){
	//module_addhook("newday");
	module_addeventhook("village","\$seen=get_module_pref(\"seen\", \"sorry\");return (\$seen>0?0:100);");
	return true;
}

function sorry_uninstall(){
	return true;
}

function sorry_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "newday":
		set_module_pref("seen",0);
		break;
	}
	return $args;
}

function sorry_runevent($type) {
	global $session;
	$session['user']['specialinc'] = "module:sorry";
	$seen=get_module_pref("seen");


	$op = httpget('op');
	if ($op == "") {
		output("`7While you are listening to others chatting, a poor looking nin approaches you. `n`n");
		addnav("See what he wants","village.php?op=shop");
		addnav("Walk away","village.php?op=nope");
	}elseif($op=="nope"){
		output("`7You decide not to meddle with that guy.`n");
		$session['user']['specialinc'] = "";
	}else{
		set_module_pref('seen',1);
		$deathoverlord= getsetting('deathoverlord','`$Ramius');
		output("`7He says, \"`xHi, I am `1Neji`x the server owner. I am sorry for the recent outages and want to give you some compensation.`7\". `n`nHe looks very sorry for what happened and seems to really care.`n`n");
		require_once("modules/inventory/lib/itemhandler.php");
		$result=add_item_by_id(82,1);
		output("`2You are given a `\$specialty elixier`2.`n");
		switch ($session['user']['dragonkills']) {
			case 0: case 1:
				$result=add_item_by_id(85,1);
				output("`2You are given a `\$health elixier 2`2.`n");
				break;
			case 2: case 3: case 4:
				$result=add_item_by_id(86,1);
				output("`2You are given a `\$health elixier 3`2.`n");
				break;
			case 5: case 6: case 7: case 8:
				$result=add_item_by_id(87,1);
				output("`2You are given a `\$health elixier 4`2.`n");
				break;
			default:	
				$result=add_item_by_id(88,1);
				output("`2You are given a `\$health elixier 5`2.`n");
		}
		$session['user']['specialinc'] = "";
	}
}

?>
