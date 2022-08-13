<?php

function kagetitles_getmoduleinfo(){
$info = array(
	"name"=>"Kagetitles",
	"version"=>"1.0",
	"author"=>"`2Oliver Brendel",
	"category"=>"Titles",
	"download"=>"",
	
	);
	return $info;
}

function kagetitles_install(){
	module_addhook("dragonkilltext");
	module_addhook_priority("setrace",INT_MAX);
	module_addhook("rock");
	return true;
}

function kagetitles_uninstall(){
	return true;
}

function kagetitles_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
		case "setrace": case "dragonkilltext":
			$title=kagetitles_gettitle($session['user']['dragonkills'],$session['user']['race'],$session['user']['sex'],false);
			$newtitle=$title;
			require_once("lib/names.php");
			$newname = change_player_title($title);
			$session['user']['title'] = $title;
			$session['user']['name'] = $newname;

		
		break;
		case "rock":
			addnav("Tetsubo");
			addnav("Title Change to Japanese","runmodule.php?module=kagetitles&op=titles");
			break;
	}
	return $args;
}

function kagetitles_run(){
	global $session;
	$op=httpget('op');
	$name="`gT`xe`gt`xsu`tbo";
	page_header("%s",sanitize($name));
	$u=&$session['user'];
	addnav("Navigation");
	addnav("Back to the rock","rock.php");
	addnav("Actions");
	switch ($op) {
		case "changetitle":
			$title=kagetitles_gettitle($session['user']['dragonkills'],$session['user']['race'],$session['user']['sex'],true);
			$newtitle=$title;
			require_once("lib/names.php");
			debug($newtitle);debug($title);
			$newname = change_player_title($title);
			$session['user']['title'] = $title;
			$session['user']['name'] = $newname;
			output("`yAll set! Come back again...");
			break;
		case "overview":
			output("`yYou approach %s`y and ask about Japanese Titles... which are available and what they mean....`n`n",$name);
			$titles=array(
				"Genin"=>"下忍",
				"Chuunin"=>"中忍",
				"Jounin"=>"上忍",
				"Hunter Nin"=>"追い忍",
				"Kage"=>"影",
				"Hokage"=>"火影",
				"Mizukage"=>"水影",
				"Kazekage"=>"風影",
				"Otokage"=>"音影",
				"Raikage"=>"雷影",
				"Tsuchikage"=>"土影",
				);
			$wromanji=translate_inline("Rōmaji");
			$wkanji=translate_inline("Kanji");
			rawoutput("<center><table cellpadding='3' cellspacing='0' border='0' ><tr class='trhead'><td>$wromanji</td><td>$wkanji</td></tr>");
			$class='';
			foreach ($titles as $romanji=>$kanji) {
				$class=($class=='trlight'?'trdark':'trlight');
				rawoutput("<tr class='$class'><td>");
				output_notl("`@$romanji");
				rawoutput("</td><td>");
				output_notl("`2$kanji");
				rawoutput("</tr>");
			}
			rawoutput("</table>");
			addnav("Back to the titles","runmodule.php?module=kagetitles&op=titles");
			break;
		case "titles":
			output("`yYou approach %s`y and ask for Japanese Titles... you are explained that the following titles you get are in Rōmaji, like most Japanese things in the game, but you may here change it at any time to the Japanese counterparts. This does only affect your Orochimaru-Kill-Title... if you have a custom title, it has a priority.`n`n`\$Do you want to change your title?",$name);
			require_once("lib/names.php");
			require_once("lib/titles.php");
			$title=kagetitles_gettitle($session['user']['dragonkills'],$session['user']['race'],$session['user']['sex'],false);
			debug($u['title']);
			debug($title);
			if ($u['title']==$title) {
				addnav("`xChange it `\$NOW`x please","runmodule.php?module=kagetitles&op=changetitle");
				output("`yPreview:`n`n`iBefore`i: %s`n`y`iAfter`i: %s",$u['title'],kagetitles_gettitle($session['user']['dragonkills'],$session['user']['race'],$session['user']['sex'],true));
			} else {
				output("Sadly, you do not have the standard title for your Orochimaru Kill Level... either you got a custom one by an event, or already have the Japanese one...");
			}
			addnav("Overview of available titles","runmodule.php?module=kagetitles&op=overview");
			break;
	
	}
	page_footer();
}

function kagetitles_gettitle($dk,$race,$sex=SEX_MALE,$jp=FALSE) {
	require_once("lib/titles.php");
	$title=$ktitle=get_dk_title($dk,$sex);
	switch ($race) {
		case "Sand":
			$add="`4K`qazek`Qage";
			$kadd="`)風`~影";
			break;
		case "Mist":
			$add="`1M`!izu`1kag`!e";
			$kadd="`!水`1影";
			break;
		case "Leaf":
			$add="`2H`@ok`Kag`@e";
			$kadd="`\$火`4影";
			break;
		case "Rock":
			//$add="`)T`~suchi`)k`~a`jg`)e";
			$add="`TT`esu`Mchik`eag`Te";
			$kadd="`g土`q影";
			break;
		case "Lightning":
			$add="`xR`Rai`2k`~a`@g`2e";
			$kadd="`x雷`4影";
			break;
		default:
			$add="`2H`@ok`Kag`@e";
			$kadd="`\$火`\$影";
	}
	if ($dk>0 && $dk<4) {
		$ktitle=str_replace("Genin","下忍",$ktitle);
	}
	if ($dk>=4 && $dk<8) {
		$ktitle=str_replace("Chuunin","中忍",$ktitle);
	}
	if ($dk>=8 && $dk<12) {
		$ktitle=str_replace("Jounin","上忍",$ktitle);
	}	
	if ($dk>=12 && $dk<16) {
		$ktitle=str_replace("Hunter Nin","追い忍,",$ktitle);
	}
	if ($dk>=16 && $dk<20) {
		$ktitle=str_replace("Anbu","暗部",$ktitle);
	}
	if ($dk>=21 && $dk<25) {
		$ktitle=str_replace("Anbu Captain","暗部 隊長",$ktitle);
	}
	if ($dk>=26 && $dk<30) {
		$ktitle=str_replace("Sannin","三忍",$ktitle);
	}
	if ($dk>=30 && $dk<50) {
		$ktitle="影";
	}
	
	if ($dk>=50 && $dk <=80) {
		$title=$add;
		$ktitle=$kadd;
	}

	if ($dk>=81) {
		$title="`1M`!aster {$add}";
		$ktitle="`1M`!aster ".$kadd;
	}
	if ($dk>=100) {
		$title="`4G`\$rand {$add}";
		$ktitle="`4G`\$rand ".$kadd;
	}
	if ($dk>=500) {
		$title="`tD`qaimyō";
		$ktitle="`t藩`q侯";
	}
	if ($jp) {
		return $ktitle;
	} else {
		return $title;
	}
	

}

?>
