<?php

function avatar_getimage($race, $gender, $set,$bio=false,$user=0) {
	$usedefault = 0;
	$file = "modules/avatar/$set/$race-$gender.gif";
	if (!file_exists($file)) {
		$usedefault = 1;
		$file = "modules/avatar/default.gif";
	}
	if (get_module_pref("avatar","avatar",$user)!='' && $bio)  {
		if (get_module_pref("validated","avatar",$user)) {
			$picname=str_replace(" ","",get_module_pref("avatar","avatar",$user));
			$image="<img align='left' src='".$picname."' ";
			if (filter_var($picname, FILTER_VALIDATE_URL)===false) {
				// url invalid
				if (filter_var("http://test.com".$picname,FILTER_VALIDATE_URL)===false) 
					return "";
			}
			if (get_module_setting("restrictsize","avatar")) {
				$dimensions=get_module_pref('avatar_resize_dimensions','avatar',$user);
				$maxwidth = get_module_setting("maxwidth","avatar");
				$maxheight = get_module_setting("maxheight","avatar");
/*				if ($dimensions!='') {
					$dimensions=explode("|",$dimensions);
					$resizedheight=$dimensions[0];
					$resizedwidth=$dimensions[1];
				} else {
*/					//stripped lines from Anpera's avatar module =)
					//changed that to an implementation of imagemagick
					if (!@fopen($picname,'r')) {
						set_module_pref('validated',0,'avatar',$user);
						return "<p>Missing Picture<br/>Bad URL!</p>";
					}
					try {
						$imageBlob = file_get_contents($picname);
						$imagick = new Imagick();
						$imagick->readImageBlob($imageBlob);
						$pic_height = $imagick->getImageHeight();
						$pic_width = $imagick->getImageWidth();
					} catch (Exception $e) {
						output("Sorry, something went wrong getting that pic: %s", $e->getMessage());
					}
				//	$pic_size = @getimagesize($picname); // GD2 required here - else size always is recognized as 0
				//	$pic_width = $pic_size[0];
				//	$pic_height = $pic_size[1];
					//other arguments are channels,bits etc
					//damn that effing gd2
					if ($pic_width=='') {
						$pic_width=$maxwidth;
					}	
					if ($pic_height=='') {
						$pic_height=$maxheight;
					}
					//aspect ratio. We are scaling for height/width ratio
					$resizedwidth=$pic_width;
					$resizedheight=$pic_height;
					if ($pic_height > $maxheight) {
						$resizedheight=$maxheight;
						$resizedwidth=round($pic_width*($maxheight
/$pic_height));
					}
					if ($resizedwidth > $maxwidth) {
						$resizedheight=round($resizedheight*($maxwidth
/$resizedwidth));
						$resizedwidth=$maxwidth;
						
					}
//					set_module_pref('avatar_resize_dimensions',implode("|",array($resizedheight,$resizedwidth)),'avatar',$user);
//				}
				$image.=" height=\"$resizedheight\"  width=\"$resizedwidth\" ";
				
			}
			$image.=">";
		} else {
			$image=translate_inline("Avatar not validated yet");
		}
	} else {
		$l = translate_inline("Licensed for use in LoTGD");
		$image = "<center><img align='center' src='$file'>$l</img>";
		if (!$usedefault) {
			require("modules/avatar/$set/setinfo.php");
			$image .= "<br><center>$setcopy<br>$l</center>";
		}
	}
	return $image;
}

function avatar_showimages($set) {
	$races = modulehook("racenames");
	rawoutput("<table cellpadding='0' cellspacing='0' border='0' bgcolor='#999999'>");
	$r = translate_inline("Race");
	$m = translate_inline("Male Image");
	$f = translate_inline("Female Image");
	rawoutput("<tr class='trhead'><th>$r</th><th>$m</th><th>$f</th></tr>");
	$i = 0;
	foreach ($races as $key=>$race) {
		$r = strtolower($race);
		$imm = avatar_getimage($r, "male", $set);
		$imf = avatar_getimage($r, "female", $set);
		rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
		rawoutput("<th>");
		output_notl('`^');
		output($race);
		output_notl("`0");
		rawoutput("</th><td>");
		rawoutput($imm);
		rawoutput("</td><td>");
		rawoutput($imf);
		rawoutput("</td>");
		rawoutput("</tr>");
		$i++;
	}
	rawoutput("</table>");
}

function avatar_showsets() {
	$setnames = array();
	$setdirs = array();

	addnav("Image sets");
	$dir = "modules/avatar";
	$d = opendir($dir);
	while (($file = readdir($d)) !== false) {
		if ($file[0] == '.') continue;
		if (is_dir($dir . "/" . $file)) {
			// okay, this is a possible set
			$f = $dir . "/" . $file . "/" . "setinfo.php";
			if (file_exists($f)) {
				require($f);
				$setnames[$setindex] = $setname;
				$setdirs[$setindex] = $file;
			}
		}
	}
	closedir($d);

	// Now display the sets in order.
	ksort($setnames);
	ksort($setdirs);
	reset($setdirs);
	while(list($key, $val) = each($setdirs)) {
		addnav($setnames[$key],
				"runmodule.php?module=avatar&op=view&set=$val");
	}
}

function avatar_get_all_images($race, $gender, $selset, $button) {
	$setnames = array();
	$setdirs = array();

	$dir = "modules/avatar";
	$d = opendir($dir);
	while (($file = readdir($d)) !== false) {
		if ($file[0] == '.') continue;
		if (is_dir($dir . "/" . $file)) {
			// okay, this is a possible set
			$f = $dir . "/" . $file . "/" . "setinfo.php";
			if (file_exists($f)) {
				require($f);
				$setnames[$setindex] = $setname;
				$setdirs[$setindex] = $file;
			}
		}
	}
	closedir($d);

	// Now display the sets in order.
	ksort($setnames);
	ksort($setdirs);
	reset($setdirs);
	$str = "<table border=0>";
	while(list($key, $val) = each($setdirs)) {
		$str .= "<tr>";
		// We are going to do three per row here
		$str .= "<td>" . $setnames[$key] .
			"<br /><input type='radio' name='set' value='" .
			$setdirs[$key] . "'";
		if ($setdirs[$key] == $selset) $str .= " checked";
		$str .= "></td><td>";
		$str .= avatar_getimage($race, $gender, $setdirs[$key]);
		$str .= "</td>";

		// second
		if(list($key, $val) = each($setdirs)) {
			$str .= "<td>" . $setnames[$key] .
				"<br /><input type='radio' name='set' value='" .
				$setdirs[$key]."'";
			if ($setdirs[$key] == $selset) $str .= " checked";
			$str .= "></td><td>";
			$str .= avatar_getimage($race, $gender, $setdirs[$key]);
			$str .= "</td>";
		} else {
			$str .= "<td>&nbsp;</td><td>&nbsp;</td>";
		}

		// third
		if(list($key, $val) = each($setdirs)) {
			$str .= "<td>" . $setnames[$key] .
				"<br /><input type='radio' name='set' value='" .
				$setdirs[$key]."'";
			if ($setdirs[$key] == $selset) $str .= " checked";
			$str .= "></td><td>";
			$str .= avatar_getimage($race, $gender, $setdirs[$key]);
			$str .= "</td>";
		} else {
			$str .= "<td>&nbsp;</td><td>&nbsp;</td>";
		}

		$str .= "</tr>";
	}

	if ($button !== false) {
		$str .= "<tr><td colspan=6 align=center>";
		$str .= "<input type='submit' class='button' value='$button'>";
		$str .= "</td></tr>";
	}
	$str .= "</table>";
	return $str;
}
?>
