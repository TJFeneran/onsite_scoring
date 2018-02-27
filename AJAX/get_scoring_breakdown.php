<?php

	include("../includes/util.php");
	$eventid = intval($_POST["eventid"]);
	
	$sql = "SELECT tbl_competition_awards.name AS awardname, tbl_competition_awards.lowest, tbl_competition_awards.highest FROM tbl_competition_awards WHERE eventid='$eventid' ORDER BY tbl_competition_awards.highest DESC";
	$res = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($res) > 0) {
		while($row = mysql_fetch_assoc($res)) {
			$row["highest"] = floor($row["highest"] / 3);
			$row["lowest"] = floor($row["lowest"] / 3);
			$awards[] = $row;
		}
		
		print(json_encode($awards));
	}


	exit();
?>
