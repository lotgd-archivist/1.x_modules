<?php
	$clanid = $session['user']['clanid'];
	$days = (int) get_module_objpref("clans",$clanid,"days","clan_avatars");
	if ($days <= 0){
		$send = translate_inline("Preview");
		$max_byte_size = get_module_setting('uploadsize');
		$allowed_types = "(jpg|jpeg|gif|bmp|png)";
		output("%s`5 looks at you, \"`&So, you wish to assign an image to be your clan's avatar?",getsetting('clanregistrar','`%Karissa'));
		output("If that is the case, please show me the image (Upload not more than `v%s`& bytes large, allowed file endings are %s).`5\"`n`n",$max_byte_size,$allowed_types);
		output("\"`&I also have to say that you hereby state you do not violate international copyrights with your picture.");
		output("Note also, that you hand over the rights to your picture to the server as they store (longer than your account maybe exists) and display the image in the respective biographies.`5\"`n`n");
		output("Upload your avatar:`n");
		rawoutput("<form method='POST' enctype='multipart/form-data' name='upload' action='runmodule.php?module=clan_avatars&op=upload'>");
		rawoutput("<input type='file' name='file'><br><br>");
		rawoutput("<input type='submit' class='button' name='Upload' value='$send'>");
		rawoutput("</form>");
		addnav("","runmodule.php?module=clan_avatars&op=upload");
		if(httppost("Upload")) { //we received something maybe
			$errors = array(
			       0=>"There is no error, the file uploaded with success.",
			       1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini.",
			       2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
			       3=>"The uploaded file was only partially uploaded.",
			       4=>"No file was uploaded.",
			       6=>"Missing a temporary folder.",
			);
			translate_inline($errors);
			$file = $_FILES["file"];
			$error = $file['error'];
			output_notl("`\$".$errors[$error]."`n");
			if(is_uploaded_file($file["tmp_name"])) {
				if(preg_match("/\." . $allowed_types . "$/i", $file["name"])) {
					if($file["size"] <= $max_byte_size) {
						$extension=substr($file['name'],strlen($file['name'])-4,4);
						$filename = get_module_setting('uploaddir')."/clan-".$clanid."-".date("YmdHs").$extension;
						if(move_uploaded_file($file["tmp_name"], $filename)) {
							output("%s`5 smiles, \"`&To finalize, I shall need `%%s `&gems.`5\"",getsetting('clanregistrar','`%Karissa'),get_module_setting("gemcost"));
							addnav("Confirmation");
							addnav("Yes","runmodule.php?module=clan_avatars&op=yes&cost=$cost&url=".rawurlencode($filename));
							addnav("No","runmodule.php?module=clan_avatars&op=upload&cost=$cost");
							$image = "<img align='left' src='".$filename."' ";
							if (get_module_setting("restrictsize")) {
								//stripped lines from Anpera's avatar module =)
								$maxwidth = get_module_setting("maxwidth");
								$maxheight = get_module_setting("maxheight");
								$pic_size = @getimagesize($filename); // GD2 required here - else size always is recognized as 0
								$pic_width = $pic_size[0];
								$pic_height = $pic_size[1];
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
								$image.=" height=\"$resizedheight\"  width=\"$resizedwidth\" ";
							}
							$image.=">";
							rawoutput("<table><tr><td valign='top'>");
							$terms = appoencode(translate_inline("Your Clanavatar"));
							rawoutput("</td><td valign='top'>$image</td></tr><td></td><td>$terms</td></table>");
							debug ("File successfully transferred!");
							debug ("Name: " . $file["name"] . "");
							debug ("Size: " . $file["size"] . " Bytes");
							debug ("MIME-Type: " . $file["type"] . "");
							debug ("Link: <a href=\"" . $filename . "\">" . $file["name"]  . "</a>");
						}else{
							output ("The file could not be uploaded.");
						}
					}else{
						output ("You may only have a filesize up to %s bytes!",$max_byte_size );
					}
				}else{
					output ("The file extension is not supported.");
				}
			}else{
				output ("You did not specify a file to upload.");
			}
		} 
		if (get_module_setting("restrictsize")) {
			$maxwidth = get_module_setting("maxwidth");
			$maxheight = get_module_setting("maxheight");
			output("`n`nPlease note that there are regulations concerning the size.`n");
			output("You may not have an avatar that has a width of more than %s pixels or a height of more than %s pixels.",$maxwidth,$maxheight);
			output("`n`nAny larger picture will be scaled to a smaller size.");
		}
	}else{
		output("%s`5 smiles, \"`&I'm sorry, but you will have to wait %s more day(s) until you can redo your clan's avatar.`5\"",getsetting('clanregistrar','`%Karissa'),$days);
	}
?>