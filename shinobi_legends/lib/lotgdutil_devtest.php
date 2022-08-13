<?php
	require_once("common.php");
	require_once("lib/villagenav.php");
	$goldamt = $_POST['goldamt'];
	$gemamt = $_POST['gemamt'];
	if (get_module_pref('developer') == 2) { addnav("`b---Currency---`b");
		 addnav("Add Gems","runmodule.php?module=lotgdutil&mode=devtest&op=gemamt");
		 addnav("Add Gold","runmodule.php?module=lotgdutil&mode=devtest&op=goldamt");
         addnav("Dump Gold","runmodule.php?module=lotgdutil&mode=devtest&op=dumpgold");
         addnav("Dump Gems","runmodule.php?module=lotgdutil&mode=devtest&op=dumpgems");
         addnav("--------");
	}
	if (get_module_pref('fix') == 1) addnav("Fix Broken Navs","runmodule.php?module=lotgdutil&mode=fixnavs");
	if (get_module_pref('modadmin') == 1) addnav("List MYSQL Processes","runmodule.php?module=lotgdutil");
	addnav("-------");
	villagenav();
	if ($session['user']['superuser']>1) addnav("Return to the Grotto","superuser.php");
	addnav("Modules");
	if ($op=="dumpgold"){
        $session['user']['gold']=0;
        debuglog("dumped all gold in devtest");
	}
	if ($op=="dumpgems"){
        $session['user']['gems']=0;
        debuglog("dumped all gems in devtest");
	}
	
	if ($op=="goldamt"){
        output("`%How much gold would you like?`n`0");
        $linkcode="<form action='runmodule.php?module=lotgdutil&mode=devtest&op=goldamt2' method='POST'><input name='goldamt' id='goldamt'><input type='submit' class='button' value='Submit'></form>";
        output("%s",$linkcode,true);
        $linkcode="<script language='JavaScript'>document.getElementById('bet').focus();</script>";
        output("%s",$linkcode,true);
        addnav("","runmodule.php?module=lotgdutil&mode=devtest&op=goldamt2");
		addnav("`bCancel Gold Request`b","runmodule.php?module=lotgdutil&mode=devtest");
		addnav("--");
	}
	
	if ($op=="goldamt2"){
        set_module_pref('message',"`!The man takes your money and hands you %s gold.`0",$goldamt);
        $session['user']['gold']+=$goldamt;
        redirect("runmodule.php?module=lotgdutil&mode=devtest&op=get3");
	    }
	if ($op=="get3"){
	        output_notl(get_module_pref('message'));
	}
	if ($op=="gemamt"){
	        output("`%How many gems would you like?`n`0");
	        $linkcode="<form action='runmodule.php?module=lotgdutil&mode=devtest&op=gemamt2' method='POST'><input name='gemamt'id='gemamt'><input type='submit' class='button' value='Submit'></form>";
	        output("%s",$linkcode,true);
	        $linkcode="<script language='JavaScript'>document.getElementById('bet').focus();</script>";
	        output("%s",$linkcode,true);
		addnav("`bCancel Gem Request`b","runmodule.php?module=lotgdutil&mode=devtest");
		addnav("--");
		addnav("","runmodule.php?module=lotgdutil&mode=devtest&op=gemamt2");
	}
	if ($op=="gemamt2"){
        set_module_pref('message',"`!The man takes your money and hands you %s gems.`0",$goldamt);
        $session['user']['gems']+=$gemamt;
        redirect("runmodule.php?module=lotgdutil&mode=devtest&op=get3");
	}
	page_header("Developer Test Module");
	output("This module is used for hooking into so that developer's can easily hook here and test new developmental creations without letting hazardous/broken code to be open to the public.`n`n");
	output("I hope you can find a use for this script as I have.`n");
	output("Thanks,`n`#Arune");
	modulehook("devtest", array(), true);
?>