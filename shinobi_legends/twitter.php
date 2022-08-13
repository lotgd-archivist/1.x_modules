<?php

function twitter_getmoduleinfo(){
	$info = array(
			"name"=>"Twitter Display",
			"version"=>"1.0",
			"author"=>"Oliver Brendel",
			"category"=>"Administrative",
			"download"=>"",
			"description"=>"Displays a Twitter Account HTML.",
			"settings"=>array(
						"General Settings,title",
						"narutokun"=>"Register and display Narutokun?,bool|1",
			),
		);
	return $info;
}

function twitter_install(){
	module_addhook("everyfooter");
	return true;
}

function twitter_uninstall(){
	return true;
}

function twitter_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "everyfooter":
		if ($_SERVER['SCRIPT_NAME']!="/home.php") break; //only on the home

		if (!isset($args['nav'])) {

			$args['nav'] = array();

		} elseif (!is_array($args['nav'])) {

			$args['nav'] = array($args['nav']);
		}
		$display = "<div style='text-align:center; padding-left: auto; padding-right:auto;max-width: 200px;'>";
		//$display .= "<a class=\"twitter-timeline\"  href=\"https://twitter.com/NejiSL\"  data-widget-id=\"300514420239515648\">Tweets from @NejiSL</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>";
		$display .= "<a class=\"twitter-timeline\"  href=\"https://twitter.com/NejiSL\"  data-widget-id=\"300514420239515648\">Tweets from @NejiSL</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(1){
		js=d.createElement(s);
		js.id=id;
		js.src=\"//platform.twitter.com/widgets.js\";
		fjs.parentNode.insertBefore(js,fjs);
		}
		}(document,\"script\",\"twitter-wjs\");</script>";
		$display.="</div>";


		array_push($args['nav'], $display);


	break;
	}

	return $args;
}

function twitter_run(){
}
