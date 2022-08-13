<?php
function mail_download_getmoduleinfo(){
	$info = array(
		"name"=>"Mail Downloader 1.1.1nb",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Mail",
		"download"=>"",
		"settings"=>array(
			"Mail Download via FPDF,title",
			),
	);
	return $info;
}

function mail_download_install(){
	module_addhook("mailform");
	module_addhook("mailform-archive");
	module_addhook("header-mail");
	module_addhook("header-mailarchive");
	return true;
}

function mail_download_uninstall(){
	return true;
}

function mail_download_dohook($hookname,$args){
	global $session;
	$op=httpget('op');
	if (!defined('FPDF_FONTPATH'))  define('FPDF_FONTPATH','/var/www/html/naruto/fpdf/font/');
	
	require_once('fpdf/fpdf.php');
	switch ($hookname) {
		case "mailform": case "mailform-archive":
			// if (!in_array($session['user']['acctid'],array(7,37231))) break;
				$pdf=translate_inline("Grab checked as PDF");
				rawoutput("<input type='submit' name='pdf_mail' class='button' value='$pdf'>");
			break;
			
		case "header-mail": case "header-mailarchive": 
			if (httppost('pdf_mail')=='') break;
			

			$ids=httppost('msg');debug($ids);
			if (!is_array($ids)) break;
			$messages = implode(",",$ids);
			
			if ($hookname=='header-mailarchive') $mail=db_prefix('mailarchive');
				else $mail=db_prefix('mail');
			
			$sql="SELECT a.*, b.name as sender, c.name as receiver FROM $mail AS a LEFT JOIN ".db_prefix('accounts')." AS b ON a.msgfrom=b.acctid LEFT JOIN ".db_prefix('accounts')." AS c on a.msgto=c.acctid WHERE messageid IN ($messages);";
			
			$result=db_query($sql);

			$u=&$session['user'];
			$name=sanitize($u['name']);
			$header=sprintf_translate('Mail Protocol for %s @ `nhttps://shinobilegends.com`n`n',$name);
			$deleted=translate_inline("Deleted User");
			$fromto="Message from %s to %s\nDate %s GMT +1";

			
			$pdf=new FPDF();
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',16);
			// $pdf->Cell(40,10,'Hallo Welt!');		
			
			$pdf->Write(12,mail_convert($header)); 
			
			$pdf = new FPDF(); 

/* // Neue Seite erzeugen
$pdf->AddPage(); 

// Schriftart festlegen
$pdf->SetFont('Arial', '', 14); 

$pdf->Write(5, 'Besuchen Sie '); 

// Die Schriftfarbe auf Blau festlegen
$pdf->SetTextColor(0, 0, 255); 
// Unterstreichung als Textformatierung verwenden,
// die Schriftart bleibt hierbei wie vorher definiert
$pdf->SetFont('', 'U'); 

// Text mit Write als Link ausgeben
$pdf->Write(5, "www.fpdf.de \n", 'http://www.fpdf.de');
$pdf->Write(5, 'www.fpdf.de', 'http://www.fpdf.de');
$pdf->Output();exit(0); */

			while ($row=db_fetch_assoc($result)) {
				$from=sanitize($row['sender']);
				if ($from=='') $from=$deleted;
				$to=sanitize($row['receiver']);
				if ($to=='') $to=$deleted;
				$mail=full_sanitize($row['body']);
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',16);
				$pdf->Write(12,mail_convert(sprintf_translate($fromto,$from,$to,$row['sent']))."\n\n");
				$pdf->SetFont('Arial','',10);
				$pdf->Write(5,$mail);
			} 
			$pdf->Output('Shinobimails.pdf','D');
			exit(0);
			break;
	}
	return $args;
}

function mail_download_run(){
}

function mail_convert($text) {
	//$text=mb_convert_encoding($text,'UTF-8','ISO-8859-1');
	$text=str_replace('`n',"\n",$text);
	return $text;

}
?>
