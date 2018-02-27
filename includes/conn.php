<?php

	$isradix = ($_SERVER["SERVER_NAME"] == "judges.radixdance.com") ? true : false;

	include("creds.php");

	define("db_username","$un");
	define("db_password","$pw");
	define("db_host","$host");
	if($isradix)
		define("db_database","radix_stats");
	else define("db_database","dancetea_manager");
	
	
	function db_connect() {
		$mysql=mysql_connect(constant("db_host"),constant("db_username"),constant("db_password"));
		if(!$mysql) { die("Error: ".mysql_error()); }
			$db = mysql_select_db(constant("db_database"),$mysql);
		if(!$db) {	die("Error: ".mysql_error()); }	
	}

?>