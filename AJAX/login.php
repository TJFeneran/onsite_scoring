<?php
	
	include("../includes/util.php");

	$un = mysql_real_escape_string($_POST["u"]);
	$pw = mysql_real_escape_string($_POST["p"]);

	if(strlen($un) > 1 && strlen($pw) > 1) {
		
		$sql = "SELECT id,theme FROM `users` WHERE name='$un' AND password=md5('$pw') LIMIT 1";
		$res = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($res) > 0) {
			while($row = mysql_fetch_row($res)) {
				print("v");
			}	
		}
		
	}

	exit();
?>