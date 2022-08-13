<?php

if (!defined("OVERRIDE_FORCED_NAV")) define("OVERRIDE_FORCED_NAV",true);

function commentary_download_getmoduleinfo(){
	$info = array(
		"name"=>"Mail Downloader 1.1.1nb",
		"override_forced_nav"=>true,		
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Commentary",
		"download"=>"",
		"settings"=>array(
			"Comment Download via FPDF,title",
			),
	);
	return $info;
}

function commentary_download_install(){
	module_addhook("insertcomment");
	return true;
}

function commentary_download_uninstall(){
	return true;
}

function commentary_download_dohook($hookname,$args){
	global $session;
	$op=httpget('op');
	static $run=0;
	define('FPDF_FONTPATH','/var/www/html/naruto/fpdf/font/');
	require_once('fpdf/fpdf.php');
	switch ($hookname) {
		case "insertcomment": 
			if ($run==1) break;
			// if (!in_array($session['user']['acctid'],array(7,37231))) break;
				$pdf=translate_inline("Grab commentlines as PDF");
				output("Lines to extract: ");
				rawoutput("<form action='runmodule.php?module=commentary_download&op=grab' method='POST'><input type='hidden' name='section' value='".$args['section']."'><select name='lines'>");
				addnav("","runmodule.php?module=commentary_download&op=grab");
				for ($i=100;$i<=1000;$i+=100) {
					$opt.="<option value='$i'>$i</option>";
				}
				rawoutput($opt."</select><input type='submit' name='pdf_mail' class='button' value='$pdf'></form>");
				$run=1;
			break;
	}
	return $args;
}

function commentary_download_run(){
	global $session;
	$op=httpget('op');
	define('FPDF_FONTPATH','/var/www/html/naruto/fpdf/font/');
	require_once('fpdf/fpdf.php');	
	switch($op) {
		case "grab":
		if (httppost('pdf_mail')=='') break;
		$section=httppost('section');
		$lines=httppost('lines');
		
		$mail=db_prefix('commentary');
		
		$sql="SELECT a.*, b.name as name FROM $mail AS a LEFT JOIN ".db_prefix('accounts')." AS b ON a.author=b.acctid WHERE section='$section' ORDER BY commentid DESC LIMIT $lines;";
		
		$result=db_query($sql);
		
		$rows=array();
		while ($row=db_fetch_assoc($result)) {
			array_unshift($rows,$row);
		}

		$u=&$session['user'];
		$name=sanitize($u['name']);
		$header=sprintf_translate("Protocol for section %s @ `nhttps://shinobilegends.com\n\n",$section);
		$deleted=translate_inline("Deleted User");
		$fromto="Message from %s to %s\nDate %s GMT +1";

		
		$pdf=new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',12);

		
		$pdf->Write(12,full_sanitize($header)); 
		
		foreach ($rows as $row) {
			$row['name']=sanitize($row['name']);
			if ($row['name']=='') $row['name']=$deleted;
			$from=$row['name'];
			$pdf->SetFont('Arial','',8);
			$pdf->Write(5,commentary_convert($row));
		} 
		$pdf->Output('ShinobiCommentary.pdf','D');
		exit(0);
		break;	
	}



}

function commentary_convert($row) {
	$row['name'] = iconv("UTF-8", "Windows-1252//TRANSLIT", $row['name']);
	$row['comment'] = iconv("UTF-8", "Windows-1252//TRANSLIT", $row['comment']);
	$row['comment']=full_sanitize($row['comment']);
	if (substr($row['comment'],0,2)=="::") {
		$row['comment']=$row['name']." ".substr($row['comment'],2);
	} elseif (substr($row['comment'],0,1)==":") {
		$row['comment']=$row['name']." ".substr($row['comment'],1);
	} elseif (substr($row['comment'],0,3)=="/me") {
		$row['comment']=$row['name']." ".substr($row['comment'],3);
	} elseif (substr($row['comment'],0,5)=="/game") {
		$row['comment']=substr($row['comment'],5);
	} else {
		$row['comment']=$row['name']." says, \"".$row['comment']."\"";
	}
	return $row['comment']."\n";

}
?>
