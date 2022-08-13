<?php

function homepagecookies_getmoduleinfo(){
$info = array(
	"name"=>"Homepage Cookie Note",
	"description"=>"Put out the notifier message that you use cookies (doh!)",
	"version"=>"1.0",
	"author"=>"`2Oliver Brendel",
	"override_forced_nav"=>true,
	"category"=>"Administrative",
	"download"=>"",
	"settings"=>array(
		),

	);
	return $info;
}

function homepagecookies_install(){
	module_addhook_priority("index",10);
	module_addhook_priority("everyhit",10);
	return true;
}

function homepagecookies_uninstall(){
	debug("`n`c`b`QNotifier Module - Uninstalled`0`b`c");
	return true;
}

function homepagecookies_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
		case "everyhit":
			if ($_COOKIE['cookiebar'] == "CookieAllowed") {
				// The user has allowed cookies, let's load our external services
				//proceed			
			} else {
				//kill the usual ones
				unset($_COOKIE['lgi']);
				unset($_COOKIE['template']);
				unset($_COOKIE['language']);
			}
			
			break;
		case "index":
/*			rawoutput('<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
<script>
window.addEventListener("load", function(){
window.cookieconsent.initialise({
  "palette": {
    "popup": {
      "background": "#252e39"
    },
    "button": {
      "background": "#14a7d0"
    }
  },
  "theme": "classic",
  "position": "bottom-right",
  "content": {
    "message": "This website uses cookies to provide our services to you."
  }
})});
</script>');
*/
		rawoutput("<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&always=1&noGeoIp=1&refreshPage=1&showNoConsent=1&noConfirm=1&hideDetailsBtn=1&showPolicyLink=1&blocking=1&remember=360&privacyPage=https%3A%2F%2Fshinobilegends.com%2Frunmodule.php%3Fmodule%3Dcreationaddon%26op%3Dprivacy%26village%3D1%26c%3D37-234544'></script>");
		if ($_COOKIE['cookiebar'] == "CookieAllowed") {
			// The user has allowed cookies, let's load our external services
			//proceed			
		} else {
			//kill the usual ones
			unset($_COOKIE['lgi']);
			unset($_COOKIE['template']);
			unset($_COOKIE['language']);
		}
		rawoutput("<br/><a href='#' onclick=\"document.cookie='cookiebar=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/'; setupCookieBar(); return false;\"><strong style='color:#ff0000;font-size:2em'>Click here to revoke the Cookie consent</strong></a><br/>");
		break;
	}
	return $args;
}

function homepagecookies_run(){
}

?>
