<?php
	require("places.php");
	//$imgname="map.gif";
	//$im = @imagecreatefromgif ($imgname); /* Attempt to open */
	$im=imagecreatefromjpeg('map.jpg');
	$bg=imagecolorallocate($im,0,0,0);
	// global $session;
	// if (((int)$session['user']['acctid'])==0) $im=0; //Not logged in
	if (!$im) { /* See if it failed */
		$im = imagecreatetruecolor (150, 30); /* Create a blank image */
		$bgc = imagecolorallocate ($im, 255, 255, 255);
		$tc = imagecolorallocate ($im, 0, 0, 0);
		imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);
		/* Output an errmsg */
		imagestring ($im, 1, 5, 5, "Error loading $imgname", $tc);
	}
	//imagepng($im,'map.png');
	//imagedestroy($im);
	// Set the enviroment variable for GD
	putenv('GDFONTPATH=' . realpath('.'));
		   //The y-ordinate. This sets the position of the fonts baseline, not
		   //the very bottom of the character.
	// Name the font to be used (note the lack of the .ttf extension)
	$font = 'DAYROM__';
	//$font= 'sazanami-mincho';
	if (!isset($_GET['loc'])) $loc="";
		else $loc=$_GET['loc'];
	
	$lock=imagecolorallocate($im,125,205,125);
	$blue=imagecolorallocate($im,9,25,255);
	foreach ($places as $key=>$place) {
		$col=imagecolorallocate($im,255,0,0);
		imagefilledellipse($im,$place['x'],$place['y'],5,5,$col);
		imagettftext($im,9,0,$place['x']+1,$place['y']-7,$col,$font,$place['name']);
		if ($loc==$key) {
			$values = array(
					$place['x']-6,		$place['y'],  // Point 1 (x, y)
					$place['x']-3,		$place['y']-3, // Point 2 (x, y)
					$place['x'],		$place['y']-6,  // Point 3 (x, y)
					$place['x']+3,	$place['y']-3,  // Point 4 (x, y)
					$place['x']+6,	$place['y'],  // Point 5 (x, y)
					$place['x']+3,	$place['y']+3,   // Point 6 (x, y)
					$place['x'],		$place['y']+6,  // Point 7 (x, y)
					$place['x']-3,	$place['y']+3,   // Point 8 (x, y)
					);
			imagefilledpolygon($im, $values, 8, $lock);
			imagettftext($im,9,0,$place['x']-75,$place['y']-5,$blue,$font,"You are here");
			$x = array(
					$place['x']-5,
					$place['x'],
					$place['x'],
					$place['x']-3,
					$place['x'],
					);
			$y = array(
					$place['y']-5,
					$place['y'],
					$place['y']-3,
					$place['y'],
					$place['y'],
					);
			for ($i=0;$i<count($x)-1;$i++) {
				imageline($im,$x[$i],$y[$i],$x[$i+1],$y[$i+1],$blue);
			}
		
		}
	}	
	//output at last
	header("Content-type: image/jpeg");
	imagejpeg($im);	
	imagedestroy($im);
?>