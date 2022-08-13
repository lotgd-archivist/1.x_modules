<?php
	global $session,$REQUEST_URI;
	
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
	return $args;
?>