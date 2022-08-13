<?php

function discord_widget_getmoduleinfo(){
$info = array(
	"name"=>"Discord Index Display",
	"version"=>"1.0",
	"author"=>"`2Oliver Brendel",
//	"override_forced_nav"=>true,
	"allowanonymous"=>true,
	"category"=>"Social",
	"download"=>"",
	);
	return $info;
}

function discord_widget_install(){
	module_addhook_priority("index_bottom",10);
	return true;
}

function discord_widget_uninstall(){
	return true;
}

function discord_widget_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
		case "index_bottom":
			rawoutput('<iframe style="display: block; margin:auto;" src="https://discord.com/widget?id=419477377349845014&theme=dark" width="350" height="500" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>');
			output("`n");
			break;
	}
	return $args;
}

function discord_widget_run() {
}
?>
