<?php

require_once("lib/commentary.php");
require_once("lib/sanitize.php");
require_once("lib/nltoappon.php");
function library_getmoduleinfo(){
	$info = array(
		"name"=>"The Library",
		"author"=>"Chris Vorndran<br>Original Idea: `QCleodelia",
		"version"=>"2.71",
		"category"=>"Library",
		"download"=>"http://dragonprime.net/users/Sichae/librarypack.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Places a Library in a village, in which users can submit their own books, or books can hook into the village.",
			"settings"=>array(
				"Library General Settings,title",
					"allow"=>"Allow users to send in their own books?,bool|1",
					"dk"=>"How many DKs does one need before they can submit a book?,int|25",
					"pp"=>"Display how many books per page?,int|10",
					"max"=>"Max amount of characters in a story,int|10000",
					"showcon"=>"Show content during validation,bool|1",
				"Library Card Settings,title",
					"ca"=>"Are Library Cards needed,bool|0",
					"caco"=>"Cost of Library Card,int|500",
				"Library Location Settings,title",
					"looa"=>"Library exists in all cities?,bool|0",
					"libraryloc"=>"Location of Library,location|".getsetting("villagename", LOCATION_FIELDS),
					"loungeloc"=>"Location of the Library's Main Lounge,location|".getsetting("villagename", LOCATION_FIELDS),
			),
			"prefs"=>array(
				"Library Preferences,title",
				"card"=>"Does this user have a library card,bool|0",
				"libaccess"=>"Has access to Library Validation?,bool|0",
		),
		);
	return $info;
}
function library_install(){
	module_addhook("moderate");
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("superuser");
	module_addhook("superuser-headlines");
	module_addhook("delete_character");
	$library=array(
			'bookid'=>array('name'=>'bookid', 'type'=>'int(11) unsigned', 'extra'=>'auto_increment'),
			'authid'=>array('name'=>'authid', 'type'=>'int(11) unsigned', 'default'=>0),
			'creator'=>array('name'=>'creator', 'type'=>'int(11) unsigned', 'default'=>0),
			'title'=>array('name'=>'title', 'type'=>'varchar(100)'), //php5 only up to 65k
			'category'=>array('name'=>'category', 'type'=>'varchar(100)', 'default'=>'None'), //php5 only up to 65k
			'content' =>array('name'=>'response', 'type'=>'text'),
			'date'=>array('name'=>'date', 'type'=>'datetime', 'default'=>DATETIME_DATEMIN),
			'validated' =>array('name'=>'validated', 'type'=>'tinyint(4)', 'default'=>0),
			'key-PRIMARY' => array('name'=>'PRIMARY', 'type'=>'primary key', 'unique'=>'1', 'columns'=>'bookid'),
			'key-one'=> array('name'=>'authid', 'type'=>'key', 'unique'=>'0', 'columns'=>'authid'),
			'key-two'=> array('name'=>'validated', 'type'=>'key', 'unique'=>'0', 'columns'=>'validated'),
			'key-three'=> array('name'=>'category', 'type'=>'key', 'unique'=>'0', 'columns'=>'category'),
		);
	require_once("lib/tabledescriptor.php");
	synctable(db_prefix("librarybooks"), $library, true);
	return true;
	}
function library_uninstall(){
	$sql = "DROP TABLE `".db_prefix("librarybooks")."`";
	db_query($sql);
	return true;
}
function library_dohook($hookname,$args){
	global $session;
	$module = httpget('module');
	switch ($hookname){
		case "village":
			if ($session['user']['location'] == get_module_setting("libraryloc") && !get_module_setting("looa")){
				tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
				tlschema();
				addnav(array("%s Public Library",get_module_setting("libraryloc")),"runmodule.php?module=library&op=enter");
					
			}elseif (get_module_setting("looa")){
				tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
				tlschema();
				addnav(array("%s Public Library",$session['user']['location']),"runmodule.php?module=library&op=enter");
			}
			break;
		case "delete_character":
			$id = $args['acctid'];
			$sql = "DELETE FROM ".db_prefix("librarybooks")." WHERE authid=$id";
			//db_query($sql);
//			don't delete them. keep them.
			break;
		case "moderate":
			$args['clibrary'] = 'The Library';
			break;
		case "superuser":
//			if (get_module_pref("libaccess") && get_module_setting("allow")){
				addnav("Validations");
				addnav("Library Book Validation","runmodule.php?module=library&op=libval&validate=1");
//			}
			break;
		case "superuser-headlines":
//			if (get_module_pref("libaccess") && get_module_setting("allow")) {
				$sql = "SELECT count(bookid) as counter FROM ".db_prefix("librarybooks")." WHERE validated=0";
				$result=db_query_cached($sql,"bookdisplay",60);
				$row=db_fetch_assoc($result);
				if ($row['counter']>0) {
					$return=array("`b`\$There are `v%s`\$ books to validate!`b`0",$row['counter']);
					$args[]=$return;
				}
//				}
			break;
		case "changesetting":
			if ($args['setting'] == "villagename") {
				if ($args['old'] == get_module_setting("libraryloc")) {
				   set_module_setting("libraryloc", $args['new']);
				}
			}
			break;
		}
	return $args;
}
function library_run(){
	global $session;
	$op = httpget('op');
	$id = httpget('id');
	$title = addslashes(httppost('title'));
	$content = addslashes(httppost('content'));
	$category = addslashes(httppost('category'));
	$page = httpget('page');
	$categories=array('Science Fiction','Romance','Poems','Thriller','Personal','Fairy Tales');
	sort($categories);
	array_unshift($categories,'None');
	
	if (!get_module_setting("looa")){
		page_header(array("%s Public Library",get_module_setting("libraryloc")));
	}else{
		page_header(array("%s Public Library",$session['user']['location']));
	}
	if ($op != "libval"){
		addnav("Navigation");
		villagenav();
	}
	switch ($op){
		case "enter":
			output("`2You walk silently into the book filled room.");
			output("The smell of old parchment fills the air.");
			output("Lining the walls are huge shelves filled with ancient looking books.");
			output("You just know the knowledge inside them would help you on your travels.");
			output("Off to the left, an elegant woman stands; obviously the Librarian.");
			output("She peers over her glasses and smiles at you, \"`^May I help ye today?`2\"");
			addnav("Branches");
			addnav("Help Desk","runmodule.php?module=library&op=desk");
			if ($session['user']['location'] == get_module_setting("loungeloc")) addnav("Lounge","runmodule.php?module=library&op=lounge");
			if (!get_module_setting("ca") || get_module_pref("card")){
				addnav("Shelves","runmodule.php?module=library&op=shelves");
			}else{
				output("`n`nIt seems that you do not have a library card...");
				output("Perhaps you should purchase one?");
			}
			break;
		case "lounge":
			output("`2You wander into a vast cafe, people crowding around the counter.");
			output("In the corner, you see a couple of chairs huddles around a fire.");
			output("Upon hearing the crackling of the fire, you decide to speak amongst your friends.`n`n");
			addcommentary();
			viewcommentary("clibrary","Softly people whisper to each other",15,"speaks softly");
			addnav("Move About");
			if (!get_module_setting("ca") || get_module_pref("card")) addnav("Shelves","runmodule.php?module=library&op=shelves");
			addnav("Return to Main Hall","runmodule.php?module=library&op=enter");
			break;
		case "shelves":
			output("`2Around you, thousands upon thousands of books stand.");
			output("Inside of them, infinite knowledge lie...");
			output("Do ye wish to indulge in the learning process?");
			addnav("Book Shelf");
			modulehook("library");
			if (get_module_setting("allow")) addnav("Player Written Novels","runmodule.php?module=library&op=player");
			addnav("Branches");
			addnav("Lounge","runmodule.php?module=library&op=lounge");
			addnav("Return to Main Hall","runmodule.php?module=library&op=enter");
			break;
		case "player":
			require("modules/library/case_player.php");
			break;
		case "buy":
			output("`2The Librarian approaches you and smiles.");
			output("\"`^So, ye wish to purchase a library card...?`2\"");
			output("She smiles once more, and pulls out the right forms.");
			if ($session['user']['gold'] >= get_module_setting("caco")){
				output("`2She pushes the forms closer, to which you sign your name: %s`2.",$session['user']['name']);
				$session['user']['gold']-=get_module_setting("caco");
				set_module_pref("card",1);
			}else{
				output("`2She withdraws the forms and shakes her head...");
				output("\"`^I am sorry %s`^, but you do not have the correct amount of %s gold for this transaction...`2\"",$session['user']['name'],get_module_setting('caco'));
				output("She shuffles around the papers and walks off.");
			}
			addnav("Return to Main Hall","runmodule.php?module=library&op=enter");
			break;
		case "desk":
			output("`2You walk over to the Librarian, as she sits behind her desk.");
			output("She looks up at you and smiles.");
			addnav("Help Topics");
			if ($session['user']['dragonkills'] >= get_module_setting("dk") && get_module_setting("allow")){
				output("`n`n\"`^Wow, you are quite the talented warrior.");
				output("I am sure that you have some stories to tell... and I would be more than honored to take them down.");
				output("Would you care to share?`2\"");
				addnav("Storytelling","runmodule.php?module=library&op=tell");
			} else {
				output("`n`n\"`^Sorry, but you should do %s more dragonkills... then you should have interesting stories to tell!`2\"`n`n",get_module_setting("dk")-$session['user']['dragonkills']);
			}

			if (get_module_setting("ca") && !get_module_pref("card")) addnav("Purchase Library Card","runmodule.php?module=library&op=buy");
				addnav("Move About");
				if (!get_module_setting("ca") || get_module_pref("card")) addnav("Shelves","runmodule.php?module=library&op=shelves");
				addnav("Return to Main Hall","runmodule.php?module=library&op=enter");
				break;
		case "tell":
			if ($title == "" && $content == ""){
				if (!httpget('su'))
					rawoutput("<form action='runmodule.php?module=library&op=tell' method='POST'>");
				else
					rawoutput("<form action='runmodule.php?module=library&op=libval&act=add' method='POST'>");
				output("`^What is the title of your story:");
				rawoutput("<input id='input' name='title' width=5>");
				output_notl("`n`n");
				output("`^Do you want to publish anonymously?:");
				rawoutput("<input id='input' type='checkbox' name='anonymous'>");
				output_notl("`n`n");
				output("`^In what category do you want to publish?:");
				rawoutput("<select name='category'>");
				foreach ($categories as $cat) {
					rawoutput("<option value='".htmlentities($cat,ENT_COMPAT,getsetting('charset','ISO-8859-1'))."'>".translate_inline($cat)."</option>");
				}
				rawoutput("</select");
				output_notl("`n`n");				
				output("`^Here, you can write in your content. You can use at most `v%s`^ characters for your story.`n`n",get_module_setting("max"));
				output("I highly suggest, that you confirm your spelling and grammar before hand.");
				output("Since we hold true to copyrights, and will not be able to edit your story.`n");
				rawoutput("<textarea name=\"content\" rows=\"10\" cols=\"60\" class=\"input\"></textarea>");
				rawoutput("<br><br><input type='submit' class='button' value='".translate_inline("Submit")."'></form>");
				rawoutput("</form>");
			}elseif ($content != "" && strlen($content) >= get_module_setting("max")){
				output("Please do not go beyond %s characters. Thank you.",get_module_setting("max"));
			}else{
				debug("Length of Content " . strlen($content));
				if (httppost('anonymous')) $authid=0;
					else $authid=$session['user']['acctid'];
				$sql = "INSERT INTO ".db_prefix("librarybooks")." (authid, title, category,content,creator,date) VALUES ('".$authid."', '".$title."', '".$category."', '".$content."',".$session['user']['acctid'].",'".date("Y-m-d H:i:s")."')";
				db_query($sql);
				output("`^Thank you very much, for telling me your story.");
				output("I shall have another librarian validate it.");
				output("If it is seen good enough, you will see it in the Library.");
				output("`n`nIf not, then you will be contacted.");
			}
			if (!httpget('su'))
				addnav("","runmodule.php?module=library&op=tell");
			else
				addnav("","runmodule.php?module=library&op=libval&act=add");
			addnav("Return to Main Hall","runmodule.php?module=library&op=enter");
			break;
		case "libval":
			require("modules/library/case_libval.php");
		break;
	case "edit":
		$sql = "SELECT title, content,category FROM ".db_prefix("librarybooks")." WHERE bookid=$id";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		rawoutput("<form action='runmodule.php?module=library&op=libval&act=edit&id=$id' method='POST'>");
		output("`^Title:");
		rawoutput("<input id='input' name='title' value='".$row['title']."' width=5>");
		output_notl("`n`n");
		output("`^Category?:");
		rawoutput("<select name='category'>");
		foreach ($categories as $cat) {
			$selected=($row['category']==$cat?'selected':'');
			rawoutput("<option value='".htmlentities($cat,ENT_COMPAT,getsetting('charset','ISO-8859-1'))."' $selected>".translate_inline($cat)."</option>");
		}
		rawoutput("</select");
		output_notl("`n`n");				
		output("`^Content.`n");
		rawoutput("<textarea name='content' rows='10' cols='60' class='input'>".htmlentities($row['content'])."</textarea>");
		rawoutput("<input type='submit' class='button' value='".translate_inline("Submit")."'></form>");
		rawoutput("</form>");
		addnav("Return to Validating","runmodule.php?module=library&op=libval");
		addnav("","runmodule.php?module=library&op=libval&act=edit&id=$id");
		break;
	}

page_footer();
}
?>
