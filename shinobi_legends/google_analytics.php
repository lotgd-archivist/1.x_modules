<?php

function google_analytics_getmoduleinfo(){
	$info = array(
		"name"=>"Google Analytics",
		"version"=>"1.1",
		"author"=>"Oliver Brendel",
		"category"=>"Statistics",
		"download"=>"",
		"settings"=>array(
		),
	);
	return $info;
}

function google_analytics_install(){
	module_addhook("everyhit");
	module_addhook("index");
	return true;
}

function google_analytics_uninstall(){
	return true;
}

function google_analytics_dohook($hookname,$args){
	switch($hookname){
		default:
		rawoutput("
		<script type=\"text/javascript\">
		var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");
		document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));
		</script>
		<script type=\"text/javascript\">
		var pageTracker = _gat._getTracker(\"UA-4795356-1\");
		pageTracker._initData();
		pageTracker._trackPageview();
		</script>
		");
	}
	return $args;
}

function google_analytics_run(){
}
?>
