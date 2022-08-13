<?php
function bingobook_changecomment() {
	global $session;
	$ac = httpget('ac');
	$comment=httppost('comment');
	if ($comment=='') {
		$comment=bingobook_getcomment($ac);
	}
	$bingoid=(int)httppost('bingoid');
	$go=httppost('go');
	if ($go!="Submit") {
		output("`qIf you want, you may add a short text for your own discretion to the entry (changeable later on):`n`n");
		rawoutput("<form action='runmodule.php?module=bingobook&op=changecomment&ac=$ac' method='POST'>");
		addnav("","runmodule.php?module=bingobook&op=changecomment&ac=$ac");
		rawoutput("<input type='hidden' name='bingoid' value='$ac'>");
		rawoutput("<textarea name='comment' cols='50' rows='10' wrap='virtual' >$comment</textarea>");
		$submit=translate_inline("Submit");
		rawoutput("<input type='submit' class='button' name='go' value='$submit'>");
		rawoutput("</form>");
	} else {
		bingobook_change($bingoid,$session['user']['acctid'],stripslashes($comment));
		output("`gYou have altered the comment for that user in your bingo book... ");
		rawoutput("<img src='modules/bingobook/devil.gif'>");
	}
}
?>
