<?php

	include("../includes/util.php");
	
	$tourdateid = intval($_POST["tourdateid"]);
	$compgroup = mysql_real_escape_string($_POST["compgroup"]);
	$position = intval($_POST["position"]);
	$judgeid = intval($_POST["judgeid"]);

	if($tourdateid > 0 && strlen($compgroup) > 0 && $position > 0 && $judgeid > 0) {
		
		$check = db_one("facultyid$position","tbl_online_scoring","tourdateid='$tourdateid' AND compgroup='$compgroup' AND facultyid$position > 0"); //if any routines with that position have a judge already, warn

		if($check > 0 && ($check != $judgeid)) {
			print(db_one("fname","tbl_staff","id='$check'")." ".db_one("lname","tbl_staff","id='$check'"));
		} else {
			print("NO");
		}
		
	}
	
	
	exit();
?>
