<?php

/*
Based on the NPC-charakters by Lonny Luberts
and commentary.php from the core code

Provides a function system_commentary($section, $comment); on everyhit
For simple system comments do (example):

if (is_module_active('mod_rp')) system_commentary(village,"`\$Add whatever you want...");

Installing this module:
- a change in lib/commentary.php is necessary:
	- in addcommantary() find:
		injectcommentary($section, $talkline, $comment, $schema);
		(ok, it's the last line...)
		and add:
		$syscomment = trim(httppost('insertsystemcommentary'));
		if ($syscomment) system_commentary($section, $syscomment);

Last Changes: 05.04.05
*/
function mod_rp_getmoduleinfo(){
	$info = array(
		"name"=>"Moderate Roleplay",
		"version"=>"1.1",
		"author"=>"Michael Jandke",
		"category"=>"General",
		"download"=>"http://dragonprime.net/users/Nathan/mod_rp.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Nathan/",
		"settings"=>array(
			"Moderate Roleplay - Module Settings,title",
			"id"=>"NPC blank_char id,int",
		),
		"prefs"=>array(
			"Moderate Roleplay - User Preferences,title",
			"mod"=>"May this User moderate RP and add describtions to the cities?,bool|0",
		),
	);
	return $info;
}

function mod_rp_install(){
	$password=$_POST['pw'];
	if (!is_module_active('mod_rp')){
		output("`4Installing NPC - Blank Character for the Moderate-RP-Module.`n");
		if ($password){
		$sql = "INSERT INTO ".db_prefix("accounts")." (login,name,sex,specialty,level,defense,attack,alive,laston,hitpoints,maxhitpoints,password,emailvalidation,title,weapon,armor,race,loggedin,superuser) VALUES ('blank_char','','0','','0','1000','1000','0','".date("Y-m-d H:i:s")."','1000','1000','".md5(md5("$password"))."','','','','','TheVoice','0','2097408')";
		db_query($sql) or die(db_error(LINK));
			if (db_affected_rows(LINK)>0){
				output("`2Installed Blank Character!`n");
			}else{
				output("`4Blank Character install failed!`n");
			}
			$sql = "SELECT acctid FROM ".db_prefix("accounts")." where login = 'blank_char'";
			$result = mysql_query($sql) or die(db_error(LINK));
			$row = db_fetch_assoc($result);
			if ($row['acctid'] > 0){
				set_module_setting("id",$row['acctid']);
				output("`2Set Accout ID for Blank Character to ".$row['acctid'].".`n");
			}else{
				output("`4Failed to Set Account ID for Blank Character!`n");
			}
		}else{
			$sqlz = "SELECT acctid FROM ".db_prefix("accounts")." where login = 'blank_char'";
			$resultz = mysql_query($sqlz) or die(db_error(LINK));
			$rowz = db_fetch_assoc($resultz);
			if ($rowz['acctid'] > 0){
			}else{
				output("Blank Character's Login will be blank_char.`n");
				output("What would you like the password for Blank Character's account to be?`n");
				output("`\$(Please enter the password before activating the module!)`n");
				$linkcode="<form action='modules.php?op=install&module=mod_rp' method='POST'>";
				output("%s",$linkcode,true);
				$linkcode="<p><input type=\"text\" name=\"pw\" size=\"37\"></p>";
				output("%s",$linkcode,true);
				$linkcode="<p><input type=\"submit\" value=\"Submit\" name=\"B1\"><input type=\"reset\" value=\"Reset\" name=\"B2\"></p>";
				output("%s",$linkcode,true);
				$linkcode="</form>";
				output("%s",$linkcode,true);
				addnav("","modules.php?op=install&module=mod_rp");
			}
		}
	}else{
		debug("Updating Moderate Roleplay Module.");
	}
	module_addhook("insertcomment");
	module_addhook("village-desc");
	module_addhook("everyhit");
	return true;
}

function mod_rp_uninstall(){
	output("`4Un-Installing Moderate Roleplay Module.`n");
	$sql = "DELETE FROM ".db_prefix("accounts")." where acctid='".get_module_setting('id')."'";
	mysql_query($sql);
	output("Blank Character deleted.`n");
	return true;
}

function mod_rp_dohook($hookname,$args){
	global $session,$REQUEST_URI;
	
	switch ($hookname) {
	case "insertcomment":
		if (get_module_pref("mod")==1) {
			require_once("lib/sanitize.php");
			output("`n`b`&Moderate:`b`n");
			$section = $args['section'];
			
			rawoutput("<script language='JavaScript'>
			function previewsystext(t){		// nur so ein biﬂchen hingebogen, kann sicher noch verbessert werden...
				var out = '<span class=\'colLtWhite\'>';
				var end = '</span>';
				var x=0;
				var y='';
				var z='';
				for (; x < t.length; x++){
					y = t.substr(x,1);
					if (y=='<'){
						out += '&lt;';
						continue;
					}else if(y=='>'){
						out += '&gt;';
						continue;
					}else if (y=='`'){
						if (x < t.length-1){
							z = t.substr(x+1,1);
							if (z=='0'){
								out += '</span>';
							}else if (z=='1'){
								out += '</span><span class=\'colDkBlue\'>';
							}else if (z=='2'){
								out += '</span><span class=\'colDkGreen\'>';
							}else if (z=='3'){
								out += '</span><span class=\'colDkCyan\'>';
							}else if (z=='4'){
								out += '</span><span class=\'colDkRed\'>';
							}else if (z=='5'){
								out += '</span><span class=\'colDkMagenta\'>';
							}else if (z=='6'){
								out += '</span><span class=\'colDkYellow\'>';
							}else if (z=='7'){
								out += '</span><span class=\'colDkWhite\'>';
							}else if (z=='!'){
								out += '</span><span class=\'colLtBlue\'>';
							}else if (z=='@'){
								out += '</span><span class=\'colLtGreen\'>';
							}else if (z=='#'){
								out += '</span><span class=\'colLtCyan\'>';
							}else if (z=='$'){
								out += '</span><span class=\'colLtRed\'>';
							}else if (z=='%'){
								out += '</span><span class=\'colLtMagenta\'>';
							}else if (z=='^'){
								out += '</span><span class=\'colLtYellow\'>';
							}else if (z=='&'){
								out += '</span><span class=\'colLtWhite\'>';
							}else if (z==')'){
								out += '</span><span class=\'colLtBlack\'>';
							}else if (z=='Q'){
								out += '</span><span class=\'colDkOrange\'>';
							}else if (z=='q'){
								out += '</span><span class=\'colOrange\'>';
							}else if (z=='V'){
								out += '</span><span class=\'colBlueViolet\'>';
							}else if (z=='v'){
								out += '</span><span class=\'coliceviolet\'>';
							}else if (z=='x'){
								out += '</span><span class=\'colburlywood\'>';
							}else if (z=='X'){
								out += '</span><span class=\'colbeige\'>';
							}else if (z=='y'){
								out += '</span><span class=\'colkhaki\'>';
							}else if (z=='Y'){
								out += '</span><span class=\'coldarkkhaki\'>';
							}else if (z=='k'){
								out += '</span><span class=\'colaquamarine\'>';
							}else if (z=='K'){
								out += '</span><span class=\'coldarkseagreen\'>';
							}else if (z=='l'){
								out += '</span><span class=\'collightsalmon\'>';
							}else if (z=='L'){
								out += '</span><span class=\'colsalmon\'>';
							}else if (z=='m'){
								out += '</span><span class=\'colwheat\'>';
							}else if (z=='M'){
								out += '</span><span class=\'coltan\'>';
							}				
							x++;
						}
					}else{
						out += y;
					}
				}
				document.getElementById(\"previewsystext\").innerHTML=out+end+'<br/>';
			}
			</script>
			");
			
			$req = comscroll_sanitize($REQUEST_URI)."&comment=1";
			$req = str_replace("?&","?",$req);
			if (!strpos($req,"?")) $req = str_replace("&","?",$req);
			addnav("",$req);
			output_notl("<form action=\"$req\" method='POST' autocomplete='false'>",true);
			output_notl("<input name='insertsystemcommentary' id='syscommentary' onKeyUp='previewsystext(document.getElementById(\"syscommentary\").value);'; size='40' maxlength='800'>",true);
			if ($section=="' or '1'='1"){
				$vname = getsetting("villagename", LOCATION_FIELDS);
				$iname = getsetting("innname", LOCATION_INN);
				$sections = commentarylocs();
				reset ($sections);
				output_notl("<select name='section'>",true);
				while (list($key,$val)=each($sections)){
					output_notl("<option value='$key'>$val</option>",true);
				}
				output_notl("</select>",true);
			}else{
				output_notl("<input type='hidden' name='section' value='$section'>",true);
			}
			$add = translate_inline("Add Moderation");
			output_notl("<input type='submit' class='button' value='$add'>",true);
			output_notl("<div id='previewsystext'></div></form>",true);
		}
		break;
	case "village-desc":
		$loc = $session['user']['location'];
		if (get_module_pref("mod")==1) {
			modulehook("collapse{");
			$edit = translate_inline("Edit Describtion");
			output_notl("`n`7[<a href='runmodule.php?module=mod_rp&op=change'>`2$edit</a>`7]`n`0",true);
			addnav("","runmodule.php?module=mod_rp&op=change");
			modulehook("}collapse");
		}
		$rpdesc = get_module_setting($loc);
		if ($rpdesc!="") {
			output_notl("`&`n$rpdesc`n`0");
		}
		break;
	case "everyhit":
		function system_commentary($section,$comment) {
			if ($comment != ""){
				require_once("lib/commentary.php");
				$blankID = get_module_setting("id","mod_rp");
				injectrawcomment($section, $blankID,":".$comment);
			}
		}
		break;
	}
	return $args;
}

function mod_rp_run() {
	global $session;
	require_once("lib/villagenav.php");
	$loc = $session['user']['location'];
	$op = httpget('op');
	page_header("Describtion in $loc");
	if ($op=="change") {
		output("`n`c`b`&Describtion in %s`b`c", $loc);
		output("`n`7(The describtion entered here will be shown in the village describtion. To remove it, simply save an empty textbox.)`n`n`0");
		$text = get_module_setting($loc);
		rawoutput("<form action='runmodule.php?module=mod_rp&op=save' method='POST'>");
		addnav("","runmodule.php?module=mod_rp&op=save");
		global $output;
		$output.="<textarea name='vildesc' class='input' rows='6' cols='70'>".$text."</textarea>";
		$save = translate_inline("Save Describtion");
		rawoutput("<br><br><input type='submit' class='button' value='$save'></form>");
		villagenav();
	} elseif ($op=="save") {
		$vildesc = httppost('vildesc');
		set_module_setting($loc,$vildesc);
		redirect("runmodule.php?module=mod_rp&op=change");
	}
	page_footer();
}

?>