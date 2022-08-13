<?php
	page_header("List Procs");
	$haveapat = $_SERVER['SERVER_SOFTWARE'];
	if ($op == "apache"){
	$apat=apache_get_version();
	output("`#Apache server Info: %s`n`n",$apat);
	$apatmods = apache_get_modules();
	output("`3Loaded Modules: `2 ");
	for ($i=0;$i<200;$i++){
		if ($apatmods[$i] <> ""){
			output_notl($apatmods[$i].", ");
		}else{
			$i = 201;
		}
	}
	output("`n`n");
	output("`@-APACHE ENVIRONMENT-`n");
	output("`#SERVER_NAME: %s `n",apache_getenv(SERVER_NAME));
	output("`3SERVER_ADMIN: %s `n",apache_getenv(SERVER_ADMIN));
	output("`#HTTP_HOST: %s `n",apache_getenv(HTTP_HOST));
	output("`3HTTP_KEEP_ALIVE: %s `n",apache_getenv(HTTP_KEEP_ALIVE));
	output("`#HTTP_ACCEPT_ENCODING: %s `n",apache_getenv(HTTP_ACCEPT_ENCODING));
	output("`3HTTP_CONNECTION: %s `n",apache_getenv(HTTP_CONNECTION));
	output("`#REQUEST_METHOD: %s `n",apache_getenv(REQUEST_METHOD));
	output("`3HTTP_ACCEPT: %s `n",apache_getenv(HTTP_ACCEPT));
	output("`#HTTP_ACCEPT_CHARSET: %s `n",apache_getenv(HTTP_ACCEPT_CHARSET));
	output("`3SERVER_PORT: %s `n",apache_getenv(SERVER_PORT));
	output("`#SERVER_SIGNATURE: ");
	rawoutput(apache_getenv(SERVER_SIGNATURE));
	output("`n");
	output("`3HTTP_ACCEPT_LANGUAGE: %s `n",apache_getenv(HTTP_ACCEPT_LANGUAGE));
	output("`#SERVER_PROTOCOL: %s `n",apache_getenv(SERVER_PROTOCOL));
	output("`3PATH: %s `n",apache_getenv(PATH));
	output("`#GATEWAY_INTERFACE: %s `n",apache_getenv(GATEWAY_INTERFACE));
	output("`3SERVER_ADDR: %s `n",apache_getenv(SERVER_ADDR));
	output("`#DOCUMENT_ROOT: %s `n",apache_getenv(DOCUMENT_ROOT));
	output("`3MOD_PERL: %s `n",apache_getenv(MOD_PERL));
	output("`n`@-HEADERS INFORMATION-`n");
	$headers = apache_request_headers();
	foreach ($headers as $header => $val) {
	   output("`# %s : %s `n",$header,$val);
	}
	output("`n`@-RESPONSE HEADERS INFORMATION-`n");
	$headers = apache_response_headers();
	foreach ($headers as $header => $val) {
	   output("`# %s : %s `n",$header,$val);
	}
	}elseif ($op == "myinfo"){
	output("`3MySQL server version: %s`n", mysql_get_server_info());
	output("`#MySQL protocol version: %s`n", mysql_get_proto_info());
	output("`3MySQL host info: %s`n", mysql_get_host_info());
	output("`#MySQL client info: %s`n", mysql_get_client_info());
	output("`2-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-`n`n");
	}elseif ($op == "kill"){
		$id = httpget('id');
		mysql_query("KILL ".$id);
		output("`4Killed Process ".$id."`n");
		addnav("Continue","runmodule.php?module=lotgdutil");
	}elseif ($op == "status"){
		$status = explode('  ', mysql_stat());
		for ($i=0;$i<200;$i++){
		if ($status[$i] <> ""){
			output_notl($status[$i]."`n");
		}else{
			$i = 201;
		}
		}
		addnav("Continue","runmodule.php?module=lotgdutil");
	}elseif ($op == ""){
	$result = mysql_query("SHOW FULL PROCESSLIST");
	output("Current DB: ".$DB_NAME."`n");
	output("`#ID - Host - Database - Command - Time - State - Info`n");
	$color = "`3";
	while ($row = mysql_fetch_assoc($result)){
	   	if ($row["Info"] == "") $row["Info"] = "[NULL]";
		if ($row["State"] == "") $row["State"] = "[NULL]";
	   	if (strlen($row["Info"]) > 100){
			$row["Info"] = substr($row["Info"],0,100);
		}
	   	output($color);
		output("%s - %s - %s - %s - %s - %s - %s`n", $row["Id"], $row["Host"], $row["db"],$row["Command"], $row["Time"], $row["State"], $row["Info"]);
		if ($row["Time"] > get_module_setting('timeout') AND $row["Command"] == "Sleep" AND $row["Info"] == "[NULL]" AND $row["db"] <> ""){
			mysql_query("KILL ".$row["Id"]);
			output("`4Killed Process %s`n",$row["Id"]);
			addnav(array("Kill Process: %s",$row["Id"]),"runmodule.php?module=lotgdutil&op=kill&id=".$row["Id"]);
		}
		if ($color == "`3"){
			$color = "`#";
		}else{
			$color = "`3";
		}
	}
	mysql_free_result($result);
	}
	if ($op == ""){
		addnav("Refresh","runmodule.php?module=lotgdutil&mode=listproc");
	}else{
		addnav("Running Processes","runmodule.php?module=lotgdutil");
	}
	if (strstr($haveapat,"Apache") AND $op <> "apache") addnav("Apache Info","runmodule.php?module=lotgdutil&op=apache");
	if ($op <> "myinfo") addnav("MySql Info","runmodule.php?module=lotgdutil&op=myinfo");
	if ($op <> "status") addnav("MySql Status","runmodule.php?module=lotgdutil&op=status");
	addnav("Return to Groto","superuser.php");
?>