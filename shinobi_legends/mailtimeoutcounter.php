<?php


function mailtimeoutcounter_getmoduleinfo(){
	$info = array(
		"name"=>"Mailtimeoutcounter",
		"version"=>"1.0",
		"author"=>"Oliver Brendel",
		"category"=>"Mail",
		"download"=>"",
	);
	return $info;
}

function mailtimeoutcounter_install() {
	module_addhook("mailfunctions");
	return true;
}

function mailtimeoutcounter_uninstall() {
 return true;
}


function mailtimeoutcounter_dohook($hookname, $args){
	global $session;
	switch ($hookname) {
	case "mailfunctions":
		if (httpget('op')!='write') break;
		if ($session['user']['acctid']!=7) break;
		$targetdate=strtotime("+ ".getsetting('LOGINTIMEOUT',300)." seconds");
		$year=date("Y",$targetdate);
		$month=date("m",$targetdate);
		$day=date("d",$targetdate);
		$hour=date("H",$targetdate);
		$minute=date("i",$targetdate);
		$second=date("s",$targetdate);
		output_notl("`0");
	  rawoutput("
	  <script language='JavaScript'>
	 // Ziel-Datum in MEZ
	 var year=".$year.", month=".$month.", day=".$day.", hour=".$hour.", minute=".$minute.", second=".$second.";
	 var targetDate=new Date(year,month-1,day,hour,minute,second);

	 function countdown() {
		startDate=new Date();
		if(startDate<=targetDate) {

		 var jahre=0, monate=0, tage=0, stunden=0, minuten=0, sekunden=0;

		 if(startDate<targetDate) {
			while(startDate<targetDate) {
			 if(startDate.setFullYear(startDate.getFullYear()+1)<=targetDate) jahre++;
			}
			startDate.setFullYear(startDate.getFullYear()-1);
		 }

		 var restTage=0;
		 var dummy='';
		 var m=startDate.getMonth();
		 if(m==1-1|| m==3-1||m==5-1||m==7-1||m==8-1||m==10-1||m==12-1)
			 restTage=31-startDate.getDate();
		 else if(m==4-1|| m==6-1||m==9-1||m==11-1) restTage=30-startDate.getDate();
		 else if(m==2-1) {
			if(startDate.getFullYear()%4==0 && (startDate.getFullYear()%100!=0
				|| startDate.getFullYear()%400==0))
					restTage=29-startDate.getDate(); // Schaltjahr
			else restTage=28-startDate.getDate();
		 }

		 var startTag=startDate.getDate();
		 var zielTag=targetDate.getDate();
		 startDate.setDate(1);
		 targetDate.setDate(1);

		 if(startDate<targetDate) {
			while(startDate<targetDate) {
			 if(startDate.setMonth(startDate.getMonth()+1)<=targetDate) monate++;
			}
			startDate.setMonth(startDate.getMonth()-1);
		 }

		 if(startDate.getMonth()==targetDate.getMonth()) {
			if(startTag<=zielTag) startDate.setDate(startTag);
			else {
			 monate--;
			 tage=restTage+1;
			}
		 }
		 else {
			startDate.setMonth(startDate.getMonth()+1);
			if(startTag>=zielTag) tage=restTage+1;
			else {
			 monate++;
			 startDate.setDate(startTag);
			}
		 }
		 targetDate.setDate(zielTag);

		 // Tage
		 restTage=Math.floor((targetDate-startDate)/(24*60*60*1000));
		 startDate.setTime(startDate.getTime()+restTage*24*60*60*1000);
		 tage+=restTage;

		 // Stunden
		 stunden=Math.floor((targetDate-startDate)/(60*60*1000));
		 startDate.setTime(startDate.getTime()+stunden*60*60*1000);

		 // Minuten
		 minuten=Math.floor((targetDate-startDate)/(60*1000));
		 startDate.setTime(startDate.getTime()+minuten*60*1000);

		 // Sekunden
		 sekunden=Math.floor((targetDate-startDate)/1000);

		 // Anzeige formatieren
		 if (stunden<10) stunden='0'+stunden;
		 stunden=stunden+':';
		 if (minuten<10) minuten='0'+minuten;
		minuten=minuten+':';
		if(sekunden<10) sekunden='0'+sekunden;
			
		 document.countdownform.countdowninput.value=stunden+minuten+sekunden;


		 setTimeout('countdown()',200);
		}
		// Anderenfalls alles auf Null setzen
		else document.countdownform.countdowninput.value='TIMEOUT! Login or copy the text!';
	 }
	</script>
 <body onload='countdown()'>
	<form name='countdownform'>
	 <p>
	 ");
	output_notl($text);
	rawoutput("
		<center><input readonly size='".$length."' name='countdowninput'></center>
	 </p>

	</form>
			
 </body>
	");

		break;
	default:

	break;
	}
	return $args;
}

function mailtimeoutcounter_run(){
}
?>
