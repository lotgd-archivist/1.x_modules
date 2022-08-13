<?php
function clanranks_getmoduleinfo(){
	$info = array(
			"name"=>"Clan Ranks",
			"description"=>"This gives clans the possibility to have up to 30 own defined ranks",
			"version"=>"1.0",
			"author"=>"`2Oliver Brendel`0",
			"category"=>"Clan",
			"download"=>"http://lotgd-downloads.com",
			"settings"=>array(
				"Clan Ranks - Preferences,title",
				"neededrank"=>"What is the needed rank to edit ranks?,range,1,31,1|30",
				"maxlength"=>"Max Length of a clan title,range,1,50,1|30",

				),
		     );
	return $info;
}

function clanranks_install(){
	module_addhook_priority("clanranks",50);
	if (is_module_active("clanranks")) debug("Clan Ranks updated");

	return true;
}

function clanranks_uninstall()
{
	output_notl ("Performing Uninstall on Clan Ranks. Thank you for using!`n`n");
	return true;
}


function clanranks_dohook($hookname, $args){
	global $session,$SCRIPT_NAME;
	switch($hookname) {
		case "clanranks":
			if (httpget('op')=="" && ($SCRIPT_NAME=="clan.php" || httpget('module')=="clanranks")) {
				tlschema("clans");
				addnav("Clan Options");
				tlschema();
				$sql="SELECT clanshort FROM ".db_prefix("clans")." WHERE clanid={$args['clanid']};";
				$result=db_query($sql);
				$row=db_fetch_assoc($result);
				if ($session['user']['clanid']>0) addnav(array("View titles of '`^%s`0'",$row['clanshort']),"runmodule.php?module=clanranks&op=viewtitles&clanid={$args['clanid']}");
				if ($session['user']['clanrank']>=get_module_setting('neededrank') && $session['user']['clanid']!=0 && !defined("ALREADY_DID_THESE_CLANRANKS")) {
					define("ALREADY_DID_THESE_CLANRANKS",1);	//kill multiple execution
					addnav("Editors");
					addnav("Clan Ranks Editor","runmodule.php?module=clanranks&op=editor");
				}
			}
			require_once("modules/clanranks/func.php");
			$array=clanranks_getallranks($args['clanid'],$args['ranks']);
			$args['ranks']=$array;
			break;
	}
	return $args;
}

function clanranks_run(){
	global $session;
	$dks=get_module_setting("dks");
	$op=httpget('op');
	$mode=httpget('mode');
	$clanid=$session['user']['clanid'];
	$maxlength=get_module_setting("maxlength");
	require_once("modules/clanranks/func.php");
	switch ($op) {
		case "viewtitles":
			$id=httpget('clanid');
			if ($id) $clanid=$id;
			page_header("Clan Ranks");
			addnav("Back to the Clan Hall","clan.php");
			output("`\$Overview:`n");
			$dks = translate_inline("# of Clan Rank");
			$mtit = translate_inline("Rank");
			rawoutput("<table border=0 cellspacing=0 cellpadding=2 width='100%' align='center'>");
			rawoutput("<tr class='trhead'><td>$dks</td><td>$mtit</td></tr>"); //<td>$ftit</td>
			$titlearray=clanranks_getallranks($clanid);
			while (list($key,$rank) = each ($titlearray)) {
				rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
				rawoutput("<td>");
				output_notl("`&%s`0",$key);
				rawoutput("</td><td>");
				output_notl("`2%s`0",translate_inline($rank));
				rawoutput("</td></tr>");
				$i++;
			}
			rawoutput("</table>");
			break;
		case "editor":
			page_header("Clan Rank Editor");
			addnav("Back to the Clan Hall","clan.php");
			addnav("Clan Ranks");
			addnav("Clan Rank Editor Main","runmodule.php?module=clanranks&op=editor");
			addnav("Operations");
			//mainly copy+paste from titleedit.php
			$id = httpget('id');
			$editarray=array(
					"Titles,title",
					"titleid"=>"# of Clan Rank,viewonly",
					"title"=>"Rank Title,string,$maxlength|",
					//"female"=>"Female Title,text|",
					);
			$title=httpget('title');
			$titleid=httppost('titleid');
			if ($title=="save") {
				$titleid=httppost('titleid');
				$title = httppost('title');
				$pretitle=stripslashes(rawurldecode(httpget('hardsettitle')));
				if ($pretitle) $title=$pretitle;
				//$female = httppost('female');
				$title = mb_substr($title,0,$maxlength);
				if ($id == -1) {
					if (clanranks_get_title($titleid,$clanid)) {
						$here=translate_inline("here");
						output("`^Title already exists. Nothing saved, choose a number that is not occupied`nIf you want to change the current title displayed below, please click %s.`0","<a href=runmodule.php?module=clanranks&op=editor&title=save&id=$titleid&hardsettitle=".rawurlencode($title).">$here</a>",true);
						addnav("","runmodule.php?module=clanranks&op=editor&title=save&id=$titleid&hardsettitle=".rawurlencode($title));
						$formertitle=$title;
						addnav(array("Change title `^%s`0 to `2%s`0",$titleid,$formertitle),"runmodule.php?module=clanranks&op=editor&title=save&id=$titleid&hardsettitle=".rawurlencode($title));
						$title="add";
					} else {
						clanranks_set_title($titleid,$clanid,$title);
						//clanranks_set_title($titleid,$male,$female);
						output("`^New title added.`0");
						$title = "";
					}
				}else {
					clanranks_set_title($id,$clanid,$title);
					//clanranks_set_title($tempid,$male,$female);
					output("`^Title modified.`0");
					$title = "";
				}
			} elseif ($title=="delete") {
				$sql = "DELETE FROM ".db_prefix("module_objprefs")." WHERE modulename='clanranks' AND objtype='clanranks_title' AND setting=$clanid AND objid=$id";
				$result=db_query($sql);
				output("`^Title deleted.`0");
				$title = "";
			}
			switch ($title) {
				case"add":case "edit":
					require_once("lib/showform.php");
					if ($title=="edit"){
						$titlename=clanranks_get_title($id,$clanid);
						//$female=clanranks_get_title($id,"female");
						$row = array('titleid'=>$id, 'title'=>mb_substr($titlename,0,$maxlength));//, 'female'=>$female);
					} elseif ($title=="add") {
						$row = array('titleid'=>($titleid?$titleid:1), 'title'=>mb_substr($formertitle,0,$maxlength));//, 'female'=>'');
						$editarray['titleid']="# of Clan Rank,range,1,30,1";
						$id = -1;
					}
					rawoutput("<form action='runmodule.php?module=clanranks&op=editor&title=save&id=$id' method='POST'>");
					addnav("","runmodule.php?module=clanranks&op=editor&title=save&id=$id");
					showform($editarray,$row);
					rawoutput("</form>");
					title_help();
					output_notl("`n`n");
					output("`\$Short Overview:`n");
					$dks = translate_inline("# of Clan Rank");
					$mtit = translate_inline("Rank");
					rawoutput("<table border=0 cellspacing=0 cellpadding=2 >");
					rawoutput("<tr class='trhead'><td>$dks</td><td>$mtit</td></tr>"); //<td>$ftit</td>
					$titlearray=clanranks_getallranks($clanid);
					while (list($key,$rank) = each ($titlearray)) {
						rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
						rawoutput("<td>");
						output_notl("`&%s`0",$key);
						rawoutput("</td><td>");
						output_notl("`2%s`0",translate_inline($rank));
						rawoutput("</td></tr>");
						$i++;
					}
					rawoutput("</table>");
					break;
				default:
					output("`@`c`b-=Title Editor=-`b`c");
					$ops = translate_inline("Ops");
					$dks = translate_inline("# of Clan Rank");
					$mtit = translate_inline("Rank");
					//$ftit = translate_inline("Female Title");
					$edit = translate_inline("Edit");
					$del = translate_inline("Delete");
					$delconfirm = translate_inline("Are you sure you wish to delete this title?");
					rawoutput("<table border=0 cellspacing=0 cellpadding=2 width='100%' align='center'>");
					rawoutput("<tr class='trhead'><td>$ops</td><td>$dks</td><td>$mtit</td></tr>"); //<td>$ftit</td>
					$titlearray=clanranks_getallranks($clanid);
					output("`\$Note: Clan Ranks may only contain %s chars or less (multiply counting special chars)!`n`n",$maxlength);
					while (list($key,$rank) = each ($titlearray)) {
						rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
						rawoutput("<td>[<a href='runmodule.php?module=clanranks&op=editor&title=edit&id=$key'>$edit</a>|<a href='runmodule.php?module=clanranks&op=editor&title=delete&id=$key' onClick='return confirm(\"$delconfirm\");'>$del</a>]</td>");
						addnav("","runmodule.php?module=clanranks&op=editor&title=edit&id=$key");
						addnav("","runmodule.php?module=clanranks&op=editor&title=delete&id=$key");
						rawoutput("<td>");
						output_notl("`&%s`0",$key);
						rawoutput("</td><td>");
						output_notl("`2%s`0",translate_inline($rank));
						//rawoutput("</td><td>");
						//output_notl("`6%s`0",clanranks_get_title($i,"female"));
						rawoutput("</td></tr>");
						$i++;
					}
					rawoutput("</table>");
					addnav("Functions");
					addnav("Add a Title", "runmodule.php?module=clanranks&op=editor&title=add");
					title_help();
					break;
			}
			//addnav("Operations");
			//addnav("Rank Editor","runmodule.php?module=clanranks&op=editor");
			break;
		default:

			break;
	}
	page_footer();
}


?>
