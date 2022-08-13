<?php

if(isset($_GET['showdownloadorsourcecode'])) {
  if(function_exists('file_get_contents')) {
		highlight_string(file_get_contents(__FILE__));  
	}
	else {
	  highlight_string(implode('',file(__FILE__)));  
	}
}


function bigbio_getmoduleinfo() {
	return array(
		'name' => 'Grössere Bio',
		'author' => 'Basilius "Wasili" Sauter',
		'version' => '0.3.5',
		'category' => 'Bio',
		'download' => 'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strlen($_SERVER['PHP_SELF'])-strlen(basename($_SERVER['PHP_SELF']))).'modules/bigbio.php?showdownloadorsourcecode=wasdunichtsagst...oO',
		'settings' => array(
			'Grössere Bio - Einstellungen,title',
			'biosize' => 'Viele Zeichen sind maximal erlaubt?,int|2048',
			'allowcolor' => 'Farbwechsel erlauben?,bool|1',
			'allowother' => 'Andere Tags wie c i oder b erlauben?,bool|1',
			'allowhtml' => 'Rudimentäres HTML erlauben?,bool|0',
			'blockoldbio' => 'Normale Biographie blockieren?,bool|0',
			'HTML-Einstellungen,title',
			'allowedTags' => 'Erlaubte HTML-Tags|<br><b><h1><h2><h3><h4><i><hr>'
        .'<img><li><ol><p><strong><table>'
        .'<tr><td><th><u><ul><div><span><center><p><img>',
      'stripAttrib' => 'Nichterlaubte Attribute (Durch Semikolon trennen)'.
				'|javascript;onclick;ondblclick;onmousedown;onmouseup;onmouseover;'.
				'onmousemove;onmouseout;onkeypress;onkeydown;onkeyup;onabort;'.
				'onfocus;onload;onblur;onchange;onerror;onreset;onselect;obsubmit;onunload;style',
		),
	);
}

function bigbio_install() {
  global $session;
  module_addhook('biotop');
  module_addhook('superuser');
  module_addhook('header-prefs');
  
  $tablename = db_prefix('accounts_bigbio');
  $sql = <<< SQL
  CREATE TABLE IF NOT EXISTS `$tablename` (
		`acctid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		`bio` TEXT NOT NULL ,
		`checked` enum('none','good','bad') NOT NULL DEFAULT 'none',
		`lastcheck` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`rewarded` mediumint(6) UNSIGNED NULL,
		`lastreward` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY ( `acctid` )
	) ;
SQL;

	db_query($sql);
}

function bigbio_uninstall() {
  output("Uninstalling this module... All tables are dropped.`n");
  
  $tablename = db_prefix('accounts_bigbio');
  $sql = <<< SQL
  DROP TABLE IF EXISTS `$tablename`;
SQL;
	db_query($sql);	
}

function bigbio_dohook($hn, $args) {
  global $session;
  
  switch($hn) {
    case 'biotop':
    	addnav('Sonstiges');
    	addnav('Ausführliche Biographie','runmodule.php?module=bigbio&op=bio&q=show&acctid='.RawURLEncode($args['acctid']));
    	
    	if(get_module_setting('blockoldbio') == true) {
			  $args['bio'] = '';
			}
    	break;
    	
	  case 'superuser':
	  	if($session['user']['superuser'] & SU_EDIT_USERS) {
			  addnav('Actions');
			  addnav('Spielerbiographien','runmodule.php?module=bigbio&op=editor');
			}
	  	break;
	  	
	  case 'header-prefs':
	  	addnav('Weitere Einstellungen');
    	addnav('Ausführliche Biographie','runmodule.php?module=bigbio&op=bio&q=edit');
    	addnav('Return');
    	
    	if(get_module_setting('blockoldbio') == true) {
			  $args['bio'] = '';
			}
	  	break;
	  	
	}
	
	return $args;
}

function bigbio_run() {
  global $session;
  
  Require_once './lib/http.php';
  $op = httpget('op');
  $q = httpget('q');
  $tablename = db_prefix('accounts_bigbio');
  
  switch($op) {
	  case 'editor':
	  	switch($q) {
			  
			}
	  	break;
	  	
	  default:
	  	switch($q) {
	  	  // Biographie zeigen
			  case 'show':
			  	page_header('Ausführliche Biographie');
			  	
			  	$sql = 'SELECT `bio`,`checked` FROM `'.$tablename.'` WHERE `acctid` = "'
						.addslashes(stripslashes(RawURLDecode($_GET['acctid'])))
						.'"';
					$res = db_query($sql);
					if(db_num_rows($res) > 0) {
						$row = db_fetch_assoc($res);
						
						if($row['checked'] === 'bad') {
						  output('`$Die ausführliche Biographie dieses Users wurde von den Administratoren als '
						  	.'nicht geeignet eingestuft.`n');
						}
						elseif($row['checked'] === 'none') {
						  output('`$Achtung: Die ausführliche Biographie dieses Users wurde von den Administratoren '
						  	.'noch nicht geprüft. Solltest du Teile des folgenden Textes als ungeeignet auffassen, so '
						  	.'wende dich bitte per Petition an die Administration.`0`n`n');
						  
						  output_notl(stripslashes($row['bio']),true);
						}
						else {
						  output_notl(stripslashes($row['bio']),true);
						}
					}
					else {
					  output('`$Dieser User besitzt zur Zeit keine ausführliche Biographie.');
					}
					
					addnav('Zurück');
					villagenav();
			  	break;
			  	
			  	// Biographie editieren
			  	case 'edit':
			  		page_header('Ausführliche Biographie');
			  		if($_GET['send'] == 'true') {
						  $sql = 'SELECT `bio` FROM `'.$tablename.'` WHERE `acctid` = '.intval($session['user']['acctid']);
						  $res = db_query($sql);
						  
						  //$search = array("\r","\n");
						  //$bio = str_replace($search,"\r\n",$_POST['bio']);
						  $bio = addslashes(bigbio_cleanupbio($_POST['bio']));
						  
						  if(db_num_rows($res) > 0) {
							  $sql = 'UPDATE `'.$tablename.'` SET '
									.'`bio` = "'.$bio.'", '
									.'`rewarded` = "none" '
									.'WHERE `acctid` = '.intval($session['user']['acctid']);
								$r = db_query($sql);
								if($r) {
								  output('`3Biographie aktualisiert.`n`n`0');
								}
							}
							else {
							  $sql = 'INSERT INTO `'.$tablename.'` (`acctid`,`bio`) '
							  	.'VALUES ('.intval($session['user']['acctid']).',"'.$bio.'")';
							  $r = db_query($sql);
							  
							  if($r) {
								  output('`3Biographie erstellt.`n`n`0');
								}
							}
							db_free_result($res);
						}
			  		
			  		$sql = 'SELECT `bio`,`checked`,`lastcheck`,`rewarded`,`lastreward` FROM `'
							.$tablename
							.'` WHERE `acctid` = '.intval($session['user']['acctid']);
						$res = db_query($sql);
						
						if(db_num_rows($res) > 0) {
						  $row = db_fetch_assoc($res);
						  
						  $aCheck = array(
						  	'none' => translate_inline('`^Noch nicht geprüft'),
						  	'good' => translate_inline('`2In Ordnung'),
						  	'bad' => translate_inline('`$Bio gesperrt. Überarbeite sie!'),
							);
							
							output('`3Status der Bio: %s`3.`n',$aCheck[$row['checked']]);
							output('`3Zuletzt überprüft: `^%s`3.`n', $row['lastcheck']);
							
							if($row['rewarded'] > 0) {
							  output('`3Bewertung: `^%s`3 Donationspunkte erhalten.`n', $row['rewarded']);
							  output('`3Zuletzt bewertet: `^%s`3.`n', $row['lastreward']);
							}
							else {
							  output('`3Bewertung: `^Noch nicht bewertet`3`n');
							}
							output('`3Zeichen der Bio: `^%s`3.`n', strlen($row['bio']));
							output('`3Biographie:`n`n`n');
							
							output_notl(stripslashes($row['bio']).'`n`n',true);
						}
						
						$formaction = 'runmodule.php?module=bigbio&op=bio&q=edit&send=true';
						addnav('',$formaction);
						
						rawoutput('<fieldset>'
							.'<legend>'.translate_inline('Ausführliche Biographie').'</legend>'
							.'<form action="'.$formaction.'" method="POST">'
								.'<textarea name="bio" cols="70" rows="20">'
									.(empty($row['bio'])?'':stripslashes($row['bio']))
								.'</textarea><br />'
								.'<input type="submit" value="'.translate_inline('Bestätigen').'" class="button" /><br />'
								.translate_inline('Vergesse nicht: Wenn du die Biographie änderst wird der Status '
									.'wieder auf "none" gesetzt!')
							.'</form>'
						.'</fieldset>');
						
						addnav('Zurück');
						addnav('Einstellungen','prefs.php');
						villagenav();
			  		break;
			}
	  	break;
	}
	
	page_footer();
}


function bigbio_cleanupbio($bio) {
  $allowcolor = get_module_setting('allowcolor');
  $allowother = get_module_setting('allowother');
  $allowhtml = get_module_setting('allowhtml');
  $biosize = get_module_setting('biosize');
  
  if($allowhtml == false) {
	  $bio = strip_tags($bio);
	  debug('HTML disallowed');
	}
	else {
	  $bio = removeEvilTags($bio, get_module_setting('allowedTags'));
	  debug('HTML allowed');
	}
	
	if($allowother == false) {
	  $bio = comment_sanitize($bio);
	}
	if($allowcolor == false) {
	  $bio = sanitize($bio);
	}
	
	$bio = substr($bio, 0, $biosize);
  
  return $bio;
}

function removeEvilTags($source,$allowedTags) {
   $source = strip_tags($source, $allowedTags);

   return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source, $allowedTags);
}

function removeEvilAttributes($tagSource) {
   $stripAttrib = str_replace(';','|',get_module_setting('stripAttrib'));
   return stripslashes(preg_replace("/$stripAttrib/i", 'forbidden', $tagSource));
}
?>